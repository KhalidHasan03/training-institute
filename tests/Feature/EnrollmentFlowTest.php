<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_registering_with_batch_is_auto_enrolled(): void
    {
        $batch = Batch::where('status', 'active')->first();
        $this->assertNotNull($batch, 'Seeder must provide an active batch');

        $response = $this->post('/register', [
            'name' => 'Enroll Tester',
            'email' => 'enrolltest@example.com',
            'phone' => '01899999999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'batch' => $batch->id,
        ]);

        $response->assertRedirect();

        $user = User::where('email', 'enrolltest@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->student);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $user->student->id,
            'batch_id' => $batch->id,
            'status' => 'active',
        ]);
    }

    public function test_logged_in_student_can_enroll_via_one_click(): void
    {
        $batch = Batch::where('status', 'active')->first();
        $user = User::where('email', 'student@example.com')->firstOrFail();

        $response = $this->actingAs($user)->post(route('student.enroll', $batch));

        $response->assertRedirect();
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $user->student->id,
            'batch_id' => $batch->id,
            'status' => 'active',
        ]);
    }

    public function test_duplicate_enrollment_is_rejected(): void
    {
        $batch = Batch::where('status', 'active')->first();
        $user = User::where('email', 'student@example.com')->firstOrFail();

        $this->actingAs($user)->post(route('student.enroll', $batch));
        $this->actingAs($user)->post(route('student.enroll', $batch));

        $count = $user->student->enrollments()->where('batch_id', $batch->id)->count();
        $this->assertEquals(1, $count);
    }

    public function test_guest_cannot_use_one_click_enroll(): void
    {
        $batch = Batch::where('status', 'active')->first();

        $response = $this->post(route('student.enroll', $batch));

        $response->assertRedirect(route('login'));
    }
}
