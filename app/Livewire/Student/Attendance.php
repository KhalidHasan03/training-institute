<?php

namespace App\Livewire\Student;

use App\Models\Attendance as AttendanceModel;

class Attendance extends StudentComponent
{
    public function render()
    {
        $records = AttendanceModel::with('classSession')
            ->where('student_id', $this->student?->id)
            ->where('batch_id', $this->batch?->id)
            ->orderByDesc('date')
            ->get();

        $present = $records->whereIn('status', ['present', 'late'])->count();
        $absent = $records->where('status', 'absent')->count();
        $late = $records->where('status', 'late')->count();

        return view('livewire.student.attendance', compact('records', 'present', 'absent', 'late'));
    }
}