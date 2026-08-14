<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Student $student): bool
    {
        return $user->isAdmin() || ($user->isStudent() && $user->student?->id === $student->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Student $student): bool
    {
        return $user->isAdmin() || ($user->isStudent() && $user->student?->id === $student->id);
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }
}