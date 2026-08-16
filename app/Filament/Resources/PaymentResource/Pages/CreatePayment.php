<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $payment = new Payment($data);

        return PaymentService::create($payment);
    }
}
