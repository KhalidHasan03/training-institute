<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\Trainer;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredCourses = Course::with('activeBatches')
            ->where('status', 'active')
            ->withCount('activeBatches')
            ->orderByDesc('active_batches_count')
            ->take(6)
            ->get();

        return view('public.home', [
            'featuredCourses' => $featuredCourses,
            'spotlight' => $this->spotlightStudent(),
            'stats' => [
                'students' => Student::count(),
                'courses' => Course::where('status', 'active')->count(),
                'trainers' => Trainer::where('status', 'active')->count(),
            ],
        ]);
    }

    private function spotlightStudent(): ?array
    {
        $student = Student::with(['enrollments' => fn ($q) => $q->where('status', 'active')->with(['batch.course', 'batch.trainer'])])
            ->whereHas('enrollments', fn ($q) => $q->where('status', 'active'))
            ->where('status', 'active')
            ->inRandomOrder()
            ->first();

        $enrollment = $student?->enrollments->first();
        $batch = $enrollment?->batch;

        if (! $enrollment || ! $batch) {
            return null;
        }

        $nextClass = $batch->classSessions()
            ->where('date', '>=', today())
            ->where('status', 'scheduled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        $daysRange = max(1, (int) $batch->start_date->diffInDays($batch->end_date));
        $elapsed = max(0, (int) $batch->start_date->diffInDays(today()));
        $progress = min(100, (int) round(($elapsed / $daysRange) * 100));

        $presence = Attendance::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $total = Attendance::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->count();
        $attendance = $total > 0 ? (int) round(($presence / $total) * 100) : 0;

        return [
            'student' => $student,
            'course' => $batch->course,
            'batch' => $batch,
            'progress' => $progress,
            'attendance' => $attendance,
            'nextClass' => $nextClass,
        ];
    }
}
