<?php

namespace App\Filament\Resources\Concerns;

trait AllowsTrainerAccess
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isTrainer());
    }
}