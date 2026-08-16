<?php

namespace App\Livewire\Student;

class Materials extends StudentComponent
{
    public function render()
    {
        $materials = $this->batch?->materials()
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->get() ?? collect();

        return view('livewire.student.materials', compact('materials'));
    }
}
