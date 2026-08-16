<?php

namespace App\Policies;

use App\Models\ClassSession;
use App\Models\User;

class ClassSessionPolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ClassSession $session): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ClassSession $session): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ClassSession $session): bool
    {
        return $user->isAdmin();
    }
}
