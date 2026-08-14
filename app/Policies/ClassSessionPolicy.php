<?php

namespace App\Policies;

use App\Models\ClassSession;
use App\Models\User;

class ClassSessionPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer() || $user->isStudent();
    }

    public function view(User $user, ClassSession $session): bool
    {
        return $user->hasBatchAccess($session->batch);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ClassSession $session): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($session->batch));
    }

    public function delete(User $user, ClassSession $session): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($session->batch));
    }
}