<?php

namespace App\Livewire\Student;

class Results extends StudentComponent
{
    public function render()
    {
        $results = collect();

        if ($this->student) {
            $results = $this->student->results()
                ->with('exam')
                ->whereHas('exam', fn ($q) => $q->where('batch_id', $this->batch?->id))
                ->get();
        }

        $overall = 0;
        if ($results->count()) {
            $overall = (int) round($results->sum(fn ($r) => $r->percentage) / $results->count());
        }

        return view('livewire.student.results', compact('results', 'overall'));
    }
}
