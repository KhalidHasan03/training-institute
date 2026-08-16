<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Trainer;

class PageController extends Controller
{
    public function about()
    {
        $total = Enrollment::count();
        $completed = Enrollment::where('status', 'completed')->count();

        return view('public.about', [
            'stats' => [
                'students' => Student::count(),
                'courses' => Course::where('status', 'active')->count(),
                'trainers' => Trainer::where('status', 'active')->count(),
                'completion_rate' => $total > 0 ? (int) round(($completed / $total) * 100) : 100,
            ],
        ]);
    }
}
