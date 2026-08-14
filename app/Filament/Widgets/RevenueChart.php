<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function (int $i) {
            return now()->subMonths($i);
        });

        $labels = $months->map(fn ($d) => $d->format('M Y'));
        $revenue = $months->map(fn ($d) => (float) Payment::where('status', 'completed')
            ->whereYear('payment_date', $d->year)
            ->whereMonth('payment_date', $d->month)
            ->sum('amount'));

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (BDT)',
                    'data' => $revenue->all(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => 'rgb(37, 99, 235)',
                ],
            ],
            'labels' => $labels->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}