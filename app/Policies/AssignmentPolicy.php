<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $user->hasBatchAccess($assignment->batch);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($assignment->batch));
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($assignment->batch));
    }
}