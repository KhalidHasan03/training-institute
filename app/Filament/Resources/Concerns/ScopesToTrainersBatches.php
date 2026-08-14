<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ScopesToTrainersBatches
{
    protected static function scopeQueryToTrainerBatches(Builder $query): Builder
    {
        $user = auth()->user();

        if (! $user || ! $user->isTrainer() || ! $user->trainer) {
            return $query;
        }

        return $query->whereHas('batch', fn (Builder $q) => $q->where('trainer_id', $user->trainer->id));
    }
}