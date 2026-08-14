<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin()
            || ($user->isTrainer() && $user->hasBatchAccess($attendance->batch))
            || ($user->isStudent() && $user->student?->id === $attendance->student_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($attendance->batch));
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin();
    }
}