<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class EnrollmentChart extends ChartWidget
{
    protected static ?string $heading = 'Student Enrollments';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn (int $i) => now()->subMonths($i));

        $labels = $months->map(fn ($d) => $d->format('M Y'));
        $counts = $months->map(fn ($d) => Enrollment::whereYear('enrollment_date', $d->year)
            ->whereMonth('enrollment_date', $d->month)
            ->count());

        return [
            'datasets' => [
                [
                    'label' => 'Enrollments',
                    'data' => $counts->all(),
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
