<?php

namespace App\Filament\Resources\ResultResource\Pages;

use App\Filament\Resources\ResultResource;
use App\Models\Exam;
use App\Models\Result;
use App\Services\ResultService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateResult extends CreateRecord
{
    protected static string $resource = ResultResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $exam = Exam::findOrFail($data['exam_id']);
        $result = new Result($data);

        return ResultService::create($exam, $result);
    }
}
