<?php

namespace App\Filament\Widgets;

use App\Models\ClassSession;
use Filament\Widgets\Widget;

class UpcomingClasses extends Widget
{
    protected static string $view = 'filament.widgets.upcoming-classes';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    public function getSessions()
    {
        return ClassSession::with(['batch.course', 'trainer'])
            ->where('date', '>', today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
    }
}
