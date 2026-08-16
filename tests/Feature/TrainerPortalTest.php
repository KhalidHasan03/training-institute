<?php

namespace Tests\Feature;

use App\Livewire\Trainer\Attendance as TrainerAttendance;
use App\Livewire\Trainer\Batches;
use App\Livewire\Trainer\Dashboard;
use App\Livewire\Trainer\Exams;
use App\Livewire\Trainer\Profile;
use App\Livewire\Trainer\Sessions;
use App\Livewire\Trainer\Students;
use App\Models\Batch;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class TrainerPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function trainerUser(): User
    {
        return User::where('email', 'trainer@example.com')->firstOrFail();
    }

    protected function trainer(): Trainer
    {
        return $this->trainerUser()->trainer()->firstOrFail();
    }

    public function test_trainer_routes_load(): void
    {
        $user = $this->trainerUser();

        $paths = [
            '/trainer',
            '/trainer/batches',
            '/trainer/sessions',
            '/trainer/attendance',
            '/trainer/exams',
            '/trainer/students',
            '/trainer/profile',
        ];

        foreach ($paths as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }

    public function test_trainer_routes_require_auth(): void
    {
        $paths = ['/trainer', '/trainer/batches', '/trainer/exams'];
        foreach ($paths as $path) {
            $this->get($path)->assertRedirect(route('login'));
        }
    }

    public function test_trainer_cannot_access_other_trainers_batches(): void
    {
        $user = $this->trainerUser();
        $otherBatch = Batch::where('trainer_id', '!=', $this->trainer()->id)->first();

        $this->assertNotNull($otherBatch);

        $this->expectException(ModelNotFoundException::class);

        Livewire::actingAs($user)
            ->test(Batches::class)
            ->call('openEdit', $otherBatch->id);
    }

    public function test_dashboard_shows_trainer_stats(): void
    {
        $user = $this->trainerUser();

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSee('Good')
            ->assertSee('Active Batches');
    }

    public function test_trainer_can_create_batch(): void
    {
        $user = $this->trainerUser();
        $course = Course::where('status', 'active')->first();

        Livewire::actingAs($user)
            ->test(Batches::class)
            ->call('openCreate')
            ->set('formName', 'TEST-01')
            ->set('formCourseId', $course->id)
            ->set('formStartDate', now()->toDateString())
            ->set('formEndDate', now()->addMonths(3)->toDateString())
            ->set('formStartTime', '10:00')
            ->set('formEndTime', '12:00')
            ->set('formMaxStudents', 25)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('batches', [
            'name' => 'TEST-01',
            'trainer_id' => $this->trainer()->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_trainer_can_edit_own_batch(): void
    {
        $user = $this->trainerUser();
        $batch = $this->trainer()->batches()->first();

        Livewire::actingAs($user)
            ->test(Batches::class)
            ->call('openEdit', $batch->id)
            ->set('formName', 'RENAMED-99')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('batches', [
            'id' => $batch->id,
            'name' => 'RENAMED-99',
        ]);
    }

    public function test_trainer_can_create_session(): void
    {
        $user = $this->trainerUser();
        $batch = $this->trainer()->batches()->first();

        Livewire::actingAs($user)
            ->test(Sessions::class)
            ->set('batchId', $batch->id)
            ->call('openCreate')
            ->set('formDate', now()->addDays(2)->toDateString())
            ->set('formStartTime', '18:00')
            ->set('formEndTime', '20:00')
            ->set('formTopic', 'Livewire deep dive')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('class_sessions', [
            'batch_id' => $batch->id,
            'topic' => 'Livewire deep dive',
        ]);
    }

    public function test_trainer_can_mark_attendance_for_session(): void
    {
        $user = $this->trainerUser();
        $batch = $this->trainer()->batches()->first();
        $session = ClassSession::where('batch_id', $batch->id)->first();
        $student = $batch->activeEnrollments()->with('student')->get()->first()->student;

        Livewire::actingAs($user)
            ->test(TrainerAttendance::class)
            ->set('batchId', $batch->id)
            ->call('selectSession', $session->id)
            ->set('statuses.'.$student->id, 'absent')
            ->call('saveAttendance')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('attendances', [
            'class_session_id' => $session->id,
            'student_id' => $student->id,
            'status' => 'absent',
        ]);
    }

    public function test_trainer_can_create_exam_and_save_results(): void
    {
        $user = $this->trainerUser();
        $batch = $this->trainer()->batches()->first();
        $student = $batch->activeEnrollments()->with('student')->get()->first()->student;

        Livewire::actingAs($user)
            ->test(Exams::class)
            ->set('batchId', $batch->id)
            ->call('openCreate')
            ->set('formTitle', 'Quarterly Exam')
            ->set('formExamDate', now()->addDays(5)->toDateString())
            ->set('formTotalMarks', 50)
            ->call('save')
            ->assertHasNoErrors();

        $exam = Exam::where('batch_id', $batch->id)->where('title', 'Quarterly Exam')->firstOrFail();

        Livewire::actingAs($user)
            ->test(Exams::class)
            ->set('batchId', $batch->id)
            ->call('openGrading', $exam->id)
            ->set('marks.'.$student->id, 45)
            ->call('saveResults')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('results', [
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'marks' => 45,
            'grade' => 'A+',
        ]);
    }

    public function test_trainer_students_page_lists_enrolled_students(): void
    {
        $user = $this->trainerUser();

        Livewire::actingAs($user)
            ->test(Students::class)
            ->assertSee('Students')
            ->assertSee($this->trainer()->batches()->first()->activeEnrollments()->first()->student->name);
    }

    public function test_trainer_profile_saves_and_changes_password(): void
    {
        $user = $this->trainerUser();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('name', 'Trainer Updated')
            ->set('expertise', 'Cloud Engineering')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('saved', true);

        $this->assertEquals('Trainer Updated', $this->trainer()->fresh()->name);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('current_password', 'password123')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('changePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_student_cannot_access_trainer_routes(): void
    {
        $student = User::where('email', 'student@example.com')->firstOrFail();

        $this->actingAs($student)->get('/trainer')->assertForbidden();
    }
}
