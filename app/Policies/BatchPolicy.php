<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer() || $user->isStudent();
    }

    public function view(User $user, Batch $batch): bool
    {
        return $user->isAdmin() || $batch->status === 'active' || $user->hasBatchAccess($batch);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Batch $batch): bool
    {
        return $user->hasBatchAccess($batch);
    }

    public function delete(User $user, Batch $batch): bool
    {
        return $user->isAdmin();
    }
}