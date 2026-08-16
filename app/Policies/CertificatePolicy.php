<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    use RestrictsToAdmin;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin() || ($user->isStudent() && $user->student?->id === $certificate->student_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->isAdmin();
    }
}
