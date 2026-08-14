<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Str;

class CertificateService
{
    public static function issue(Student $student, Course $course, array $data = []): Certificate
    {
        $certificateNumber = $data['certificate_number'] ?? self::generateCertificateNumber();
        $verificationCode = $data['verification_code'] ?? self::generateVerificationCode();

        return Certificate::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'certificate_number' => $certificateNumber,
            'verification_code' => $verificationCode,
            'issue_date' => $data['issue_date'] ?? today(),
            'status' => $data['status'] ?? 'issued',
        ]);
    }

    public static function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . now()->year . '-' . strtoupper(Str::random(8));
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    public static function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Certificate::where('verification_code', $code)->exists());

        return $code;
    }
}