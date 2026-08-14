<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function view(User $user, Exam $exam): bool
    {
        return $user->hasBatchAccess($exam->batch);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function update(User $user, Exam $exam): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($exam->batch));
    }

    public function delete(User $user, Exam $exam): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($exam->batch));
    }
}