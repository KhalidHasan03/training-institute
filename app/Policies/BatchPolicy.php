<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Batch $batch): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Batch $batch): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Batch $batch): bool
    {
        return $user->isAdmin();
    }
}
