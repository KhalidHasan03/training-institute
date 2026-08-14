<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function view(User $user, Material $material): bool
    {
        return $user->hasBatchAccess($material->batch);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTrainer();
    }

    public function update(User $user, Material $material): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($material->batch));
    }

    public function delete(User $user, Material $material): bool
    {
        return $user->isAdmin() || ($user->isTrainer() && $user->hasBatchAccess($material->batch));
    }
}