<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    public function enroll(Student $student, Batch $batch, array $data = []): Enrollment
    {
        $course = $batch->course;

        $existing = Enrollment::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'batch_id' => 'This student is already enrolled in this batch.',
            ]);
        }

        if ($batch->capacity_reached) {
            throw ValidationException::withMessages([
                'batch_id' => "Batch \"{$batch->name}\" has reached its maximum capacity ({$batch->max_students} students).",
            ]);
        }

        return DB::transaction(function () use ($student, $batch, $course, $data) {
            $courseFee = (float) ($data['course_fee'] ?? $course->fee);
            $discount = (float) ($data['discount'] ?? 0);
            $finalFee = max(0, $courseFee - $discount);

            return Enrollment::create([
                'student_id' => $student->id,
                'batch_id' => $batch->id,
                'enrollment_date' => $data['enrollment_date'] ?? now()->toDateString(),
                'course_fee' => $courseFee,
                'discount' => $discount,
                'final_fee' => $finalFee,
                'payment_status' => $finalFee <= 0 ? 'paid' : 'unpaid',
                'status' => $data['status'] ?? 'active',
            ]);
        });
    }
}