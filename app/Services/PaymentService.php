<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public static function create(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {
            $enrollment = $payment->enrollment;

            if ($payment->amount < 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment amount cannot be negative.',
                ]);
            }

            if ((float) $payment->amount > $enrollment->due) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment amount cannot exceed the outstanding due of '
                        .$enrollment->due.' '.'BDT.',
                ]);
            }

            $payment->save();

            $enrollment->update([
                'payment_status' => $enrollment->due <= 0 ? 'paid' : 'partial',
            ]);

            return $payment;
        });
    }
}
