<?php

namespace App\Filament\Resources\CertificateResource\Pages;

use App\Filament\Resources\CertificateResource;
use App\Models\Course;
use App\Models\Student;
use App\Services\CertificateService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCertificate extends CreateRecord
{
    protected static string $resource = CertificateResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $student = Student::findOrFail($data['student_id']);
        $course = Course::findOrFail($data['course_id']);

        return CertificateService::issue($student, $course, $data);
    }
}
