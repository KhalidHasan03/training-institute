<?php

namespace Tests\Feature;

use App\Livewire\Student\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StudentProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_profile_loads_and_fills_student_data(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->assertSet('name', $user->student?->name ?? $user->name)
            ->assertSet('phone', $user->student?->phone)
            ->assertSee('Profile');
    }

    public function test_profile_save_updates_student_and_user(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('name', 'Updated Student')
            ->set('phone', '01999999999')
            ->set('address', 'Dhaka, Bangladesh')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('saved', true);

        $user->refresh();
        $this->assertEquals('Updated Student', $user->name);
        $this->assertEquals('Updated Student', $user->student->name);
        $this->assertEquals('01999999999', $user->student->phone);
        $this->assertEquals('Dhaka, Bangladesh', $user->student->address);
    }

    public function test_profile_photo_upload_saves_file(): void
    {
        Storage::fake('public');
        $user = User::where('email', 'student@example.com')->firstOrFail();

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $photo = UploadedFile::fake()->createWithContent('avatar.png', $png);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('photo', $photo)
            ->call('save')
            ->assertHasNoErrors();

        $user->student->refresh();
        $this->assertNotNull($user->student->photo);
        Storage::disk('public')->assertExists($user->student->photo);
    }

    public function test_change_password_requires_current_password(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('current_password', 'wrong-password')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('changePassword')
            ->assertHasErrors('current_password');

        $this->assertFalse(\Illuminate\Support\Facades\Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_change_password_updates_hash(): void
    {
        $user = User::where('email', 'student@example.com')->firstOrFail();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('current_password', 'password123')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('changePassword')
            ->assertHasNoErrors()
            ->assertSet('current_password', null);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $user->fresh()->password));
    }
}