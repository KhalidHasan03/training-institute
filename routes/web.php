<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicCourseController;
use App\Http\Controllers\PublicTrainerController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Livewire\Student\Assignments;
use App\Livewire\Student\Attendance;
use App\Livewire\Student\Certificate;
use App\Livewire\Student\Dashboard;
use App\Livewire\Student\Materials;
use App\Livewire\Student\MyCourse;
use App\Livewire\Student\Payments;
use App\Livewire\Student\Profile;
use App\Livewire\Student\Results;
use App\Livewire\Student\Schedule;
use App\Livewire\Trainer\Attendance as TrainerAttendance;
use App\Livewire\Trainer\Batches;
use App\Livewire\Trainer\Dashboard as TrainerDashboard;
use App\Livewire\Trainer\Exams;
use App\Livewire\Trainer\Profile as TrainerProfile;
use App\Livewire\Trainer\Sessions;
use App\Livewire\Trainer\Students;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('public.home');

Route::get('/courses', [PublicCourseController::class, 'index'])->name('public.courses');
Route::get('/courses/{course:slug}', [PublicCourseController::class, 'show'])->name('public.courses.show');
Route::get('/trainers', [PublicTrainerController::class, 'index'])->name('public.trainers');
Route::get('/about', [PageController::class, 'about'])->name('public.about');

Route::get('/contact', [ContactController::class, 'show'])->name('public.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('public.contact.store');

Route::prefix('certificate')->name('public.certificates.')->group(function () {
    Route::get('/', [CertificateController::class, 'verifyForm'])->name('verify');
    Route::post('/check', [CertificateController::class, 'check'])->name('check');
    Route::get('/{certificate}/print', [CertificateController::class, 'print'])->name('print');
    Route::get('/{certificateNumber}', [CertificateController::class, 'verify'])->name('result');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('student.dashboard');
    Route::get('/my-course', MyCourse::class)->name('student.my-course');
    Route::get('/schedule', Schedule::class)->name('student.schedule');
    Route::get('/attendance', Attendance::class)->name('student.attendance');
    Route::get('/materials', Materials::class)->name('student.materials');
    Route::get('/assignments', Assignments::class)->name('student.assignments');
    Route::get('/results', Results::class)->name('student.results');
    Route::get('/payments', Payments::class)->name('student.payments');
    Route::get('/my-certificate', Certificate::class)->name('student.certificate');
    Route::get('/profile', Profile::class)->name('student.profile');
    Route::post('/enroll/{batch}', [StudentEnrollmentController::class, 'store'])->name('student.enroll');
});

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/', TrainerDashboard::class)->name('dashboard');
    Route::get('/batches', Batches::class)->name('batches');
    Route::get('/sessions', Sessions::class)->name('sessions');
    Route::get('/attendance', TrainerAttendance::class)->name('attendance');
    Route::get('/exams', Exams::class)->name('exams');
    Route::get('/students', Students::class)->name('students');
    Route::get('/profile', TrainerProfile::class)->name('profile');
});
