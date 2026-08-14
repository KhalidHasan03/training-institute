<?php

namespace App\Filament\Widgets;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Trainer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenue = Payment::where('status', 'completed')->sum('amount');
        $collected = $revenue;
        $due = Enrollment::whereIn('status', ['active', 'completed'])->get()->sum(fn (Enrollment $e) => $e->due);

        return [
            Stat::make('Total Students', Student::where('status', 'active')->count())
                ->description('Active student accounts')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->chart([7, 3, 5, 4, 8, 6, 9]),
            Stat::make('Active Courses', Course::where('status', 'active')->count())
                ->description('Courses available for enrollment')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),
            Stat::make('Running Batches', Batch::where('status', 'active')->count())
                ->description('Currently active batches')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color('warning'),
            Stat::make('Total Trainers', Trainer::where('status', 'active')->count())
                ->description('Active trainers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Total Revenue', number_format($revenue) . ' BDT')
                ->description('Collected payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Total Due', number_format($due) . ' BDT')
                ->description('Outstanding from enrollments')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
        ];
    }
}