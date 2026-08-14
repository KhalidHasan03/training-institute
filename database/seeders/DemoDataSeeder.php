<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\Batch;
use App\Models\Certificate;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Result;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\User;
use App\Services\CertificateService;
use App\Services\EnrollmentService;
use App\Services\StudentIdService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $trainerUsers = User::role('trainer')->get();
        $studentUser = User::role('student')->first();

        $trainers = [
            ['name' => 'Rahim Uddin', 'email' => 'rahim@example.com', 'expertise' => 'Web Development', 'bio' => 'Senior Full-Stack Engineer with 9+ years building scalable web apps.'],
            ['name' => 'Nusrat Jahan', 'email' => 'nusrat@example.com', 'expertise' => 'UI / UX Design', 'bio' => 'Product designer who has shipped 40+ products for startups.'],
            ['name' => 'Tanvir Ahmed', 'email' => 'tanvir@example.com', 'expertise' => 'Data Science', 'bio' => 'Data scientist and ML practitioner, ex-data engineer at fintech.'],
        ];

        $trIds = [];
        foreach ($trainers as $i => $t) {
            $trainer = Trainer::updateOrCreate(
                ['email' => $t['email']],
                [
                    'user_id' => $trainerUsers[$i]?->id ?? null,
                    'name' => $t['name'],
                    'phone' => '0181' . str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT),
                    'expertise' => $t['expertise'],
                    'bio' => $t['bio'],
                    'status' => 'active',
                ]
            );
            $trIds[] = $trainer->id;
        }

        $coursesData = [
            [
                'title' => 'Laravel Web Development',
                'slug' => 'laravel-web-development',
                'short_description' => 'Master modern PHP with Laravel, Livewire and Filament.',
                'duration' => '3 months',
                'fee' => 18000,
                'level' => 'intermediate',
                'thumb' => null,
                'highlights' => [
                    'Build complete Laravel apps from scratch',
                    'Integrate Livewire and Filament for modern UIs',
                    'Master REST APIs, auth and payments',
                    'Deploy to production like a senior dev',
                    'Portfolio-ready capstone project',
                ],
            ],
            [
                'title' => 'React & Modern JavaScript',
                'slug' => 'react-modern-javascript',
                'short_description' => 'Build fast, reactive UIs with React and the modern JS ecosystem.',
                'duration' => '2.5 months',
                'fee' => 15000,
                'level' => 'intermediate',
                'thumb' => null,
                'highlights' => [
                    'Master modern JavaScript and ES modules',
                    'Build interactive UIs with React hooks',
                    'Manage state with Context and Redux patterns',
                    'Consume real-world REST APIs',
                    'Create a deployable React application',
                ],
            ],
            [
                'title' => 'UI / UX Design Fundamentals',
                'slug' => 'ui-ux-design-fundamentals',
                'short_description' => 'Learn user-centred design from wireframes to polished prototypes.',
                'duration' => '2 months',
                'fee' => 12000,
                'level' => 'beginner',
                'thumb' => null,
                'highlights' => [
                    'Learn design thinking and user research',
                    'Create wireframes and interactive prototypes',
                    'Build design systems in Figma',
                    'Understand usability and accessibility',
                    'Deliver a polished UI project for your portfolio',
                ],
            ],
            [
                'title' => 'Data Science with Python',
                'slug' => 'data-science-python',
                'short_description' => 'Analyse data and build machine-learning models with Python.',
                'duration' => '4 months',
                'fee' => 22000,
                'level' => 'advanced',
                'thumb' => null,
                'highlights' => [
                    'Analyse and visualise data with Python',
                    'Clean and prepare real-world datasets',
                    'Build and evaluate machine-learning models',
                    'Work with Pandas, NumPy and Scikit-learn',
                    'Complete an end-to-end data project',
                ],
            ],
            [
                'title' => 'Digital Marketing Mastery',
                'slug' => 'digital-marketing-mastery',
                'short_description' => 'Grow brands online with SEO, paid ads and content strategy.',
                'duration' => '1.5 months',
                'fee' => 10000,
                'level' => 'beginner',
                'thumb' => null,
                'highlights' => [
                    'Rank higher on Google with SEO best practices',
                    'Run effective paid ad campaigns',
                    'Build a content strategy that converts',
                    'Measure results with analytics dashboards',
                    'Launch a complete digital marketing plan',
                ],
            ],
            [
                'title' => 'Flutter Mobile App Development',
                'slug' => 'flutter-mobile-app-development',
                'short_description' => 'Ship cross-platform mobile apps for iOS and Android with Flutter.',
                'duration' => '3 months',
                'fee' => 19000,
                'level' => 'intermediate',
                'thumb' => null,
                'highlights' => [
                    'Build iOS and Android apps from one Dart codebase',
                    'Design expressive UIs with Flutter widgets',
                    'Connect apps to backends and Firebase',
                    'Ship to stores and manage releases',
                    'Develop a full cross-platform app project',
                ],
            ],
        ];

        $courseIds = [];
        foreach ($coursesData as $c) {
            $course = Course::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'title' => $c['title'],
                    'short_description' => $c['short_description'],
                    'description' => $c['short_description'] . " In this hands-on program you will work on real projects, learn from industry practitioners, and graduate with a portfolio-ready skillset.",
                    'duration' => $c['duration'],
                    'fee' => $c['fee'],
                    'level' => $c['level'],
                    'thumbnail' => $c['thumb'],
                    'highlights' => $c['highlights'] ?? [],
                    'status' => 'active',
                ]
            );
            $courseIds[] = $course->id;
        }

        $studentNames = ['Ayesha Akter', 'Mahmud Hasan', 'Sadia Islam', 'Imran Hossain', 'Farhana Rahman', 'Shakil Khan'];

        $studentIds = [];
        $idService = new StudentIdService();
        $i = 0;
        foreach ($studentNames as $name) {
            $email = strtolower(Str::slug($name)) . '@example.com';
            $student = Student::updateOrCreate(
                ['email' => $email],
                [
                    'user_id' => $i === 0 ? $studentUser?->id : null,
                    'student_id' => $idService->generate(),
                    'name' => $name,
                    'phone' => '017' . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'date_of_birth' => Carbon::create(1998 + ($i % 5), 1 + ($i % 12), 5 + $i),
                    'address' => 'Dhaka, Bangladesh',
                    'status' => 'active',
                ]
            );
            $studentIds[] = $student->id;
            $i++;
        }

        $batches = [
            ['course' => 0, 'trainer' => 0, 'name' => 'LD-15', 'days' => 'Sun, Tue, Thu', 'start' => '19:00', 'end' => '21:00', 'room' => 'Room 201'],
            ['course' => 1, 'trainer' => 0, 'name' => 'RJ-08', 'days' => 'Mon, Wed', 'start' => '18:00', 'end' => '20:00', 'room' => 'Room 102'],
            ['course' => 3, 'trainer' => 2, 'name' => 'DS-03', 'days' => 'Fri, Sat', 'start' => '15:00', 'end' => '18:00', 'room' => 'Lab 3'],
            ['course' => 2, 'trainer' => 1, 'name' => 'UX-12', 'days' => 'Tue, Thu', 'start' => '17:00', 'end' => '19:00', 'room' => 'Room 205'],
        ];

        $enrollmentService = new EnrollmentService();
        $enrollments = [];

        foreach ($batches as $bi => $b) {
            $batch = Batch::updateOrCreate(
                ['name' => $b['name']],
                [
                    'course_id' => $courseIds[$b['course']],
                    'trainer_id' => $trIds[$b['trainer']],
                    'start_date' => today()->subDays(30),
                    'end_date' => today()->addDays(75),
                    'class_days' => $b['days'],
                    'start_time' => $b['start'],
                    'end_time' => $b['end'],
                    'room' => $b['room'],
                    'max_students' => 30,
                    'status' => 'active',
                ]
            );

            $studentCount = min(4, count($studentIds));
            for ($si = 0; $si < $studentCount; $si++) {
                $student = Student::find($studentIds[$si]);
                if ($student->enrollments()->where('batch_id', $batch->id)->exists()) {
                    continue;
                }
                try {
                    $enrollments[] = $enrollmentService->enroll($student, $batch, [
                        'discount' => 500,
                    ]);
                } catch (\Throwable) {
                    // ignore capacity / duplicate errors
                }
            }
        }

        foreach ($enrollments as $en) {
            Payment::updateOrCreate(
                [
                    'student_id' => $en->student_id,
                    'enrollment_id' => $en->id,
                ],
                [
                    'amount' => round((float) $en->final_fee / 2, 2),
                    'payment_date' => today()->subDays(random_int(1, 20)),
                    'payment_method' => ['bKash', 'Cash', 'Nagad'][random_int(0, 2)],
                    'transaction_id' => 'TXN-' . Str::upper(Str::random(8)),
                    'notes' => null,
                    'status' => 'completed',
                ]
            );
            $en->update(['payment_status' => 'partial']);
        }

        $completedMarked = false;
        foreach (Enrollment::all() as $en) {
            if ($completedMarked || $en->student_id === $studentIds[0]) {
                continue;
            }
            $paidSoFar = (float) $en->payments()->where('status', 'completed')->sum('amount');
            $remaining = (float) $en->final_fee - $paidSoFar;
            if ($remaining > 0) {
                Payment::create([
                    'student_id' => $en->student_id,
                    'enrollment_id' => $en->id,
                    'amount' => round($remaining, 2),
                    'payment_date' => today()->subDay(),
                    'payment_method' => 'bKash',
                    'transaction_id' => 'TXN-' . Str::upper(Str::random(8)),
                    'notes' => 'Final installment',
                    'status' => 'completed',
                ]);
            }
            $en->update([
                'payment_status' => 'paid',
                'status' => 'completed',
            ]);
            $completedMarked = true;
        }

        foreach (Batch::all() as $batch) {
            for ($d = 0; $d < 6; $d++) {
                ClassSession::updateOrCreate(
                    [
                        'batch_id' => $batch->id,
                        'date' => today()->addDays($d * 2)->toDateString(),
                    ],
                    [
                        'trainer_id' => $batch->trainer_id,
                        'start_time' => $batch->start_time,
                        'end_time' => $batch->end_time,
                        'topic' => 'Lesson ' . ($d + 1) . ': ' . $batch->course->title,
                        'room' => $batch->room,
                        'status' => $d < 2 ? 'completed' : 'scheduled',
                    ]
                );
            }

            Material::updateOrCreate(
                ['batch_id' => $batch->id, 'title' => 'Introduction Slides'],
                [
                    'description' => 'Welcome slides and course outline for ' . $batch->name . '.',
                    'type' => 'document',
                    'is_published' => true,
                ]
            );

            Assignment::updateOrCreate(
                ['batch_id' => $batch->id, 'title' => 'Assignment ' . rand(1, 2)],
                [
                    'description' => 'Complete the practical exercise covered in this week\'s class.',
                    'total_marks' => 100,
                    'deadline' => now()->addDays(10),
                    'status' => 'active',
                ]
            );

            Exam::updateOrCreate(
                ['batch_id' => $batch->id, 'title' => 'Mid Term Exam'],
                [
                    'exam_date' => today()->addDays(20),
                    'total_marks' => 100,
                    'description' => 'Covers all material up to this point.',
                ]
            );
        }

        $student = Student::find($studentIds[0]);
        if ($student) {
            Certificate::updateOrCreate(
                ['student_id' => $student->id, 'course_id' => $courseIds[0]],
                [
                    'certificate_number' => 'CERT-DEMO-001',
                    'verification_code' => 'DEMO2026ABC',
                    'issue_date' => today()->subDays(5),
                    'status' => 'issued',
                ]
            );
        }

        Announcement::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'New batches are enrolling now — secure your seat today',
                'content' => 'Admission is open for our upcoming programming, design and data science batches.',
                'audience' => 'all',
                'published_by' => $trainerUsers->first()?->id,
                'published_at' => now(),
                'status' => 'published',
            ]
        );
    }
}