<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\Widget;

class RecentPayments extends Widget
{
    protected static string $view = 'filament.widgets.recent-payments';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 1;

    public function getPayments()
    {
        return Payment::with(['student'])
            ->latest('payment_date')
            ->take(6)
            ->get();
    }
}