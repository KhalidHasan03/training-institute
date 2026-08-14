<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Batch;
use App\Models\Student;
use App\Services\EnrollmentService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $student = Student::findOrFail($data['student_id']);
        $batch = Batch::findOrFail($data['batch_id']);

        return app(EnrollmentService::class)->enroll($student, $batch, $data);
    }
}