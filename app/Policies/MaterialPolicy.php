<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Material $material): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Material $material): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Material $material): bool
    {
        return $user->isAdmin();
    }
}
