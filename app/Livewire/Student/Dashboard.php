<?php

namespace App\Livewire\Student;

class Dashboard extends StudentComponent
{
    public function getUpcomingSessionsProperty()
    {
        $batch = $this->batch;

        if (! $batch) {
            return collect();
        }

        return $batch->classSessions()
            ->where('date', '>', today())
            ->where('status', 'scheduled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.student.dashboard');
    }
}