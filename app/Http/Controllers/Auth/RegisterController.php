<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Batch;
use App\Models\Student;
use App\Models\User;
use App\Services\EnrollmentService;
use App\Services\StudentIdService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $batch = null;

        if (request()->filled('batch')) {
            $batch = Batch::with('course')
                ->where('status', 'active')
                ->find(request('batch'));
        }

        return view('public.register', compact('batch'));
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data, $request) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'status' => 'active',
            ]);
            $user->assignRole('student');

            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => app(StudentIdService::class)->generate(),
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'status' => 'active',
            ]);

            if ($request->filled('batch') && $data['batch'] ?? null) {
                $batch = Batch::where('status', 'active')->find($data['batch']);

                if ($batch) {
                    app(EnrollmentService::class)->enroll($student, $batch);
                }
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('student.dashboard');
    }
}