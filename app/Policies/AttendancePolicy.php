<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin();
    }
}
