<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Exam $exam): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Exam $exam): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Exam $exam): bool
    {
        return $user->isAdmin();
    }
}
