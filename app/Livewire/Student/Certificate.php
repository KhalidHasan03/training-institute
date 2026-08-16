<?php

namespace App\Livewire\Student;

class Certificate extends StudentComponent
{
    public function render()
    {
        $certificates = $this->student?->certificates()
            ->with('course')
            ->where('status', 'issued')
            ->get() ?? collect();

        $pendingCourseIds = [];
        if ($this->student) {
            $pendingCourseIds = collect($this->student->enrollments()
                ->with('batch.course')
                ->where('status', 'completed')
                ->get())
                ->pluck('batch.course_id')
                ->unique()
                ->diff($certificates->pluck('course_id'))
                ->values()
                ->all();
        }

        return view('livewire.student.certificate', compact('certificates', 'pendingCourseIds'));
    }
}
