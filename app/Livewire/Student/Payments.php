<?php

namespace App\Livewire\Student;

class Payments extends StudentComponent
{
    public function render()
    {
        $payments = $this->student?->payments()
            ->with('enrollment.batch')
            ->latest('payment_date')
            ->get() ?? collect();

        return view('livewire.student.payments', compact('payments'));
    }
}