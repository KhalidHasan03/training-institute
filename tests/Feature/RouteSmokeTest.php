<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_load(): void
    {
        $pages = ['/', '/courses', '/about', '/trainers', '/contact', '/register', '/login'];
        foreach ($pages as $path) {
            $this->get($path)->assertStatus(200);
        }

        $batchId = Batch::where('status', 'active')->first()->id;
        $this->get("/register?batch={$batchId}")->assertOk();

        $batch = Batch::where('status', 'active')->first();
        $course = $batch->course;
        $this->get(route('public.courses.show', $course))->assertOk();
    }

    public function test_admin_pages_load(): void
    {
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();
        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_student_panel_loads(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();

        $paths = [
            '/dashboard',
            '/my-course',
            '/my-certificate',
            '/assignments',
            '/attendance',
            '/materials',
            '/payments',
            '/profile',
            '/results',
            '/schedule',
        ];

        foreach ($paths as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }

    public function test_bare_student_role_user_does_not_crash_student_panel(): void
    {
        $user = User::factory()->create();
        $user->syncRoles(['student']);

        $paths = ['/dashboard', '/results', '/payments', '/schedule', '/profile'];
        foreach ($paths as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }

    public function test_student_routes_require_auth(): void
    {
        $paths = ['/dashboard', '/my-course', '/results'];
        foreach ($paths as $path) {
            $this->get($path)->assertRedirect(route('login'));
        }
    }
}
