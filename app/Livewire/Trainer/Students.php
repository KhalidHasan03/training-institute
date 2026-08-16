<?php

namespace App\Livewire\Trainer;

use App\Models\Enrollment;
use Illuminate\Support\Collection;

class Students extends TrainerComponent
{
    public string $filterBatch = 'all';

    public string $search = '';

    public function getFilteredEnrollmentsProperty(): Collection
    {
        $batchIds = $this->batches->pluck('id');

        if ($batchIds->isEmpty()) {
            return collect();
        }

        $query = Enrollment::with('student', 'batch.course')
            ->whereIn('batch_id', $batchIds);

        if ($this->filterBatch !== 'all' && $this->batches->contains('id', (int) $this->filterBatch)) {
            $query->where('batch_id', (int) $this->filterBatch);
        }

        $enrollments = $query->orderBy('enrollment_date')->get();

        if ($this->search !== '') {
            $enrollments = $enrollments->filter(function (Enrollment $e) {
                return str_contains(
                    strtolower(($e->student->name ?? '').' '.($e->student->student_id ?? '').' '.($e->student->email ?? '')),
                    strtolower($this->search)
                );
            });
        }

        return $enrollments;
    }

    public function render()
    {
        return view('livewire.trainer.students', [
            'enrollments' => $this->filteredEnrollments,
        ]);
    }
}
