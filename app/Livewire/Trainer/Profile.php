<?php

namespace App\Livewire\Trainer;

use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class Profile extends TrainerComponent
{
    use WithFileUploads;

    public ?string $name = null;

    public ?string $phone = null;

    public ?string $expertise = null;

    public ?string $bio = null;

    public $photo;

    public bool $saved = false;

    public ?string $current_password = null;

    public ?string $new_password = null;

    public ?string $new_password_confirmation = null;

    public function mount(): void
    {
        $trainer = $this->trainer;
        $user = auth()->user();

        $this->name = $trainer?->name ?? $user->name;
        $this->phone = $trainer?->phone ?? $user->phone;
        $this->expertise = $trainer?->expertise;
        $this->bio = $trainer?->bio;
    }

    public function updatedPhoto(): void
    {
        $this->validate([
            'photo' => ['image', 'max:2048'],
        ]);
    }

    public function save()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'expertise' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->photo) {
            $path = $this->photo->store('trainers', 'public');
            $data['photo'] = $path;
        }

        $trainer = $this->trainer;

        if ($trainer) {
            $trainer->update($data);
        }

        auth()->user()->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
        ]);

        $this->saved = true;
        session()->flash('trainer-profile-saved', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('trainer-password-saved', 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.trainer.profile');
    }
}
