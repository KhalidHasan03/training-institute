<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Batch;
use App\Models\Student;
use App\Services\EnrollmentService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $student = Student::findOrFail($data['student_id']);
        $batch = Batch::findOrFail($data['batch_id']);

        return app(EnrollmentService::class)->enroll($student, $batch, $data);
    }
}
