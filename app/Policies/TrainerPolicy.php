<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;

class TrainerPolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Trainer $trainer): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->trainer?->id === $trainer->id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Trainer $trainer): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->trainer?->id === $trainer->id);
    }

    public function delete(User $user, Trainer $trainer): bool
    {
        return $user->isAdmin();
    }
}