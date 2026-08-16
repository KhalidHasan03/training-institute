<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPanelContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dashboard_shows_stats_and_content(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk()
            ->assertSee('Course Progress')
            ->assertSee('Attendance')
            ->assertSee('Pending Assignments')
            ->assertSee('Payment Due');
    }

    public function test_my_course_shows_course_info(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $student = $user->student;
        $batch = $student->enrollments()->where('status', 'active')->first()->batch;

        $response = $this->actingAs($user)->get('/my-course');
        $response->assertOk()
            ->assertSee($batch->course->title)
            ->assertSee('Course Progress')
            ->assertSee('Payment Summary');
    }

    public function test_schedule_shows_sessions(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $response = $this->actingAs($user)->get('/schedule');
        $response->assertOk()->assertSee('Class Schedule');
    }

    public function test_results_page_handles_empty_gracefully(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $response = $this->actingAs($user)->get('/results');
        $response->assertOk();
    }

    public function test_payments_shows_history(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $response = $this->actingAs($user)->get('/payments');
        $response->assertOk()->assertSee('Payment History');
    }

    public function test_certificate_page_renders(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $response = $this->actingAs($user)->get('/my-certificate');
        $response->assertOk();
    }

    public function test_sidebar_and_header_render_personalized(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();
        $student = $user->student;

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk()
            ->assertSee($student->student_id)
            ->assertSee('Student Portal');
    }
}
