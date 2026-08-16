<?php

namespace App\Services;

use App\Models\Student;

class StudentIdService
{
    public function generate(): string
    {
        $year = now()->year;
        $count = Student::whereYear('created_at', $year)->count() + 1;

        do {
            $id = sprintf('STU-%d-%04d', $year, $count);
            $count++;
        } while (Student::where('student_id', $id)->exists());

        return $id;
    }
}
