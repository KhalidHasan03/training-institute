<?php

namespace App\Livewire\Trainer;

use App\Models\Batch;
use App\Models\Trainer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.trainer')]
abstract class TrainerComponent extends Component
{
    public ?int $batchId = null;

    public function getTrainerProperty(): ?Trainer
    {
        return auth()->user()->trainer;
    }

    public function getBatchesProperty(): Collection
    {
        $trainer = $this->trainer;

        if (! $trainer) {
            return collect();
        }

        return $trainer->batches()
            ->with('course')
            ->orderBy('name')
            ->get();
    }

    public function resolveBatchId(): ?int
    {
        $batches = $this->batches;

        if ($batches->isEmpty()) {
            return null;
        }

        $requested = (int) request()->query('batch');
        $selected = $this->batchId ?: session('trainer_batch_id');

        $candidate = $requested ?: $selected;

        if ($candidate && $batches->firstWhere('id', (int) $candidate)) {
            if ($requested) {
                session(['trainer_batch_id' => (int) $candidate]);
            }

            return (int) $candidate;
        }

        return $batches->first()->id;
    }

    public function getBatchProperty(): ?Batch
    {
        $id = $this->resolveBatchId();

        return $id ? $this->batches->firstWhere('id', $id) : null;
    }

    public function updatedBatchId(): void
    {
        if ($this->batchId && $this->batches->contains('id', $this->batchId)) {
            session(['trainer_batch_id' => $this->batchId]);
        }
    }

    public function switchBatch(int $batchId): void
    {
        $this->batchId = $batchId;
        $this->updatedBatchId();
    }

    public function getEnrolledStudentsProperty(): Collection
    {
        return $this->batch?->activeEnrollments()
            ->with('student')
            ->get()
            ->map->student
            ->filter() ?? collect();
    }

    public function getBatchCountProperty(): int
    {
        return $this->batches->count();
    }

    public function getUpcomingSessionsProperty(): Collection
    {
        $batch = $this->batch;

        if (! $batch) {
            return collect();
        }

        return $batch->classSessions()
            ->where('date', '>=', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
    }
}
