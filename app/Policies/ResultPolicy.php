<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;

class ResultPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function view(User $user, Result $result): bool
    {
        return $user->isAdmin()
            || ($user->isTrainer() && $user->hasBatchAccess($result->exam->batch))
            || ($user->isStudent() && $user->student?->id === $result->student_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function update(User $user, Result $result): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($result->exam->batch));
    }

    public function delete(User $user, Result $result): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($result->exam->batch));
    }
}