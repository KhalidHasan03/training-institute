<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->isTrainer()) {
            return $user->hasBatchAccess($enrollment->batch);
        }

        if ($user->isStudent()) {
            return $user->student?->id === $enrollment->student_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($enrollment->batch));
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin();
    }
}