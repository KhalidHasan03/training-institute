<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class AttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Attendance Overview';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $present = Attendance::where('status', 'present')->count();
        $late = Attendance::where('status', 'late')->count();
        $absent = Attendance::where('status', 'absent')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Attendance',
                    'data' => [$present, $late, $absent],
                    'backgroundColor' => ['rgb(16, 185, 129)', 'rgb(245, 158, 11)', 'rgb(239, 68, 68)'],
                ],
            ],
            'labels' => ['Present', 'Late', 'Absent'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}