<?php

namespace App\Filament\Widgets;

use App\Models\ClassSession;
use Filament\Widgets\Widget;

class TodayClasses extends Widget
{
    protected static string $view = 'filament.widgets.today-classes';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    public function getSessions()
    {
        return ClassSession::with(['batch.course', 'trainer'])
            ->whereDate('date', today())
            ->orderBy('start_time')
            ->get();
    }
}