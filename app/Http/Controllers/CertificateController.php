<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verifyForm()
    {
        return view('public.certificate-verify');
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'certificate_number' => ['required', 'string', 'max:60'],
        ]);

        return redirect()->route('public.certificates.result', $data['certificate_number']);
    }

    public function verify(string $certificateNumber)
    {
        $certificate = Certificate::with(['student', 'course'])
            ->where('certificate_number', $certificateNumber)
            ->where('status', 'issued')
            ->firstOrFail();

        return view('public.certificate-result', compact('certificate'));
    }

    public function print(Certificate $certificate)
    {
        abort_unless($certificate->status === 'issued', 404);

        $certificate->load(['student', 'course']);

        return view('public.certificate-print', compact('certificate'));
    }
}