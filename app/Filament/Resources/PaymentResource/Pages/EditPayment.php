<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Services\PaymentService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->fill($data);

        return PaymentService::create($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
