<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentEnrollmentController extends Controller
{
    public function store(Request $request, Batch $batch)
    {
        abort_unless($batch->status === 'active', 404);

        $student = $request->user()->student;

        if (! $student) {
            return back()->withErrors(['enroll' => 'Your account is not linked to a student profile. Please contact the institute office.']);
        }

        $existing = Enrollment::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->where('status', 'active')
            ->exists();

        if ($existing) {
            return back()->with('enrolled', "You are already enrolled in batch \"{$batch->name}\".");
        }

        try {
            app(EnrollmentService::class)->enroll($student, $batch);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }

        return redirect()
            ->route('student.dashboard')
            ->with('enrolled', "You're now enrolled in \"{$batch->course->title}\" (Batch {$batch->name}).");
    }
}
