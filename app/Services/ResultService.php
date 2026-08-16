<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Result;
use Illuminate\Validation\ValidationException;

class ResultService
{
    public static function create(Exam $exam, Result $result): Result
    {
        return self::save($exam, $result);
    }

    public static function update(Exam $exam, Result $result): Result
    {
        return self::save($exam, $result);
    }

    private static function save(Exam $exam, Result $result): Result
    {
        $marks = (float) $result->marks;
        $total = (float) $exam->total_marks;

        if ($marks > $total) {
            throw ValidationException::withMessages([
                'marks' => "Marks ({$marks}) cannot exceed total marks ({$total}).",
            ]);
        }

        $result->grade = self::gradeForPercentage(($marks / ($total ?: 1)) * 100);
        $result->save();

        return $result;
    }

    public static function gradeForPercentage(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
    }
}
