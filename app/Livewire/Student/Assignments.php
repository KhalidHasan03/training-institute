<?php

namespace App\Livewire\Student;

class Assignments extends StudentComponent
{
    public function render()
    {
        $assignments = $this->batch?->assignments()
            ->orderByDesc('deadline')
            ->get() ?? collect();

        return view('livewire.student.assignments', compact('assignments'));
    }
}
