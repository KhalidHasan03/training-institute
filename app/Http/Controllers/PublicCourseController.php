<?php

namespace App\Http\Controllers;

use App\Models\Course;

class PublicCourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('activeBatches.trainer')
            ->where('status', 'active')
            ->withCount('activeBatches')
            ->orderBy('title')
            ->paginate(9)
            ->withQueryString();

        return view('public.courses', compact('courses'));
    }

    public function show(Course $course)
    {
        abort_unless($course->status === 'active', 404);

        $course->load([
            'activeBatches.trainer',
            'activeBatches' => fn ($q) => $q->withCount('activeEnrollments'),
        ]);

        $enrolledBatchIds = [];
        if (auth()->check() && auth()->user()->isStudent() && auth()->user()->student) {
            $enrolledBatchIds = auth()->user()->student->enrollments()
                ->whereIn('batch_id', $course->activeBatches->pluck('id'))
                ->where('status', 'active')
                ->pluck('batch_id')
                ->all();
        }

        $related = Course::where('status', 'active')
            ->whereKeyNot($course->id)
            ->take(3)
            ->get();

        return view('public.course-detail', compact('course', 'related', 'enrolledBatchIds'));
    }
}
