<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\Widget;

class RecentEnrollments extends Widget
{
    protected static string $view = 'filament.widgets.recent-enrollments';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 1;

    public function getEnrollments()
    {
        return Enrollment::with(['student', 'batch.course'])
            ->latest('enrollment_date')
            ->take(6)
            ->get();
    }
}