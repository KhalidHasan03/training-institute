<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchSwitcherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function student(): Student
    {
        return User::where('email', 'student@example.com')->firstOrFail()->student;
    }

    public function test_dashboard_defaults_to_first_active_batch(): void
    {
        $student = $this->student();
        $first = $student->enrollments()->where('status', 'active')->orderBy('enrollment_date')->first();

        $response = $this->actingAs($student->user)->get('/dashboard');
        $response->assertOk()
            ->assertSee($first->batch->name)
            ->assertSee($first->batch->course->title);
    }

    public function test_switcher_lists_all_active_batches(): void
    {
        $student = $this->student();

        $response = $this->actingAs($student->user)->get('/dashboard');
        $response->assertOk();

        foreach ($student->enrollments()->where('status', 'active')->get() as $enrollment) {
            $response->assertSee($enrollment->batch->name);
        }
    }

    public function test_batch_query_param_switches_dashboard_content(): void
    {
        $student = $this->student();
        $enrollments = $student->enrollments()->where('status', 'active')->orderBy('enrollment_date')->get();
        $second = $enrollments->get(1);

        $response = $this->actingAs($student->user)->get("/dashboard?batch={$second->batch_id}");
        $response->assertOk()
            ->assertSee($second->batch->name)
            ->assertSee($second->batch->course->title);
    }

    public function test_batch_selection_persists_across_pages_via_session(): void
    {
        $student = $this->student();
        $enrollments = $student->enrollments()->where('status', 'active')->orderBy('enrollment_date')->get();
        $second = $enrollments->get(1);

        $this->actingAs($student->user)->get("/schedule?batch={$second->batch_id}")->assertOk();
        $this->assertTrue(session('student_batch_id') === $second->batch_id);

        $this->get('/my-course')->assertOk()->assertSee($second->batch->name);
        $this->get('/payments')->assertOk()->assertSee($second->batch->name);
    }

    public function test_invalid_batch_falls_back_to_first_active_batch(): void
    {
        $student = $this->student();
        $first = $student->enrollments()->where('status', 'active')->orderBy('enrollment_date')->first();

        $response = $this->actingAs($student->user)->get('/dashboard?batch=999999');
        $response->assertOk()
            ->assertSee($first->batch->name)
            ->assertSee($first->batch->course->title);
    }
}
