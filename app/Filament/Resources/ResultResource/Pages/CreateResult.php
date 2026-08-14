<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\ResultResource;
use App\Models\Exam;
use App\Services\ResultService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResult extends CreateRecord
{
    protected static string $resource = ResultResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $exam = Exam::findOrFail($data['exam_id']);
        $result = new \App\Models\Result($data);

        return ResultService::create($exam, $result);
    }
}