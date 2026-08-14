<?php

namespace App\Livewire\Student;

class MyCourse extends StudentComponent
{
    public string $tab = 'overview';

    public function render()
    {
        $batch = $this->batch;
        $enrollment = $this->enrollment;

        $schedule = $batch?->classSessions()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get() ?? collect();

        $materials = $batch?->materials()
            ->where('is_published', true)
            ->latest()
            ->get() ?? collect();

        $assignments = $batch?->assignments()->get() ?? collect();

        $attendance = $this->student?->attendances()
            ->where('batch_id', $batch?->id)
            ->orderByDesc('date')
            ->get() ?? collect();

        $results = $this->student?->results()
            ->with('exam')
            ->whereHas('exam', fn ($q) => $q->where('batch_id', $batch?->id))
            ->get() ?? collect();

        return view('livewire.student.my-course', compact(
            'batch',
            'enrollment',
            'schedule',
            'materials',
            'assignments',
            'attendance',
            'results',
        ));
    }
}