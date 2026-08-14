<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class Profile extends StudentComponent
{
    use WithFileUploads;

    public ?string $name = null;
    public ?string $phone = null;
    public ?string $date_of_birth = null;
    public ?string $address = null;
    public $photo;
    public ?string $current_password = null;
    public ?string $new_password = null;
    public ?string $new_password_confirmation = null;
    public bool $saved = false;

    public function mount(): void
    {
        $student = $this->student;
        $user = auth()->user();

        $this->name = $student?->name ?? $user->name;
        $this->phone = $student?->phone ?? $user->phone;
        $this->date_of_birth = $student?->date_of_birth?->toDateString();
        $this->address = $student?->address;
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
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        if ($this->photo) {
            $path = $this->photo->store('students', 'public');
            $data['photo'] = $path;
        }

        $student = $this->student;

        if ($student) {
            $student->update($data);
        }

        auth()->user()->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
        ]);

        $this->saved = true;
        session()->flash('profile-saved', 'Profile updated successfully.');
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
        session()->flash('password-saved', 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.student.profile');
    }
}