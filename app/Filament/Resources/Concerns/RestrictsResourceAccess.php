<?php

namespace App\Filament\Resources\Concerns;

trait RestrictsResourceAccess
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user ? $user->isAdmin() : false;
    }
}
