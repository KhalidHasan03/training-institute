@extends('layouts.public')

@section('title', 'Certificate Verified')

@section('content')
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-2xl px-4">
            <div class="reveal relative">
                <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-emerald-500/25 to-brand-500/25 blur-2xl"></div>
                <div class="card relative !rounded-3xl p-10 text-center shadow-lift">
                    <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-glow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="h-8 w-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </span>
                    <h1 class="mt-5 font-display text-2xl font-bold text-emerald-700">This certificate is genuine</h1>
                    <p class="mt-2 text-slate-500">We verified "{{ $certificate->certificate_number }}" and found a valid credential.</p>

                    <dl class="mt-8 grid gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-6 text-left sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Certificate number</dt>
                            <dd class="mt-1 font-semibold text-navy-900">{{ $certificate->certificate_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Recipient</dt>
                            <dd class="mt-1 font-semibold text-navy-900">{{ $certificate->student->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Course</dt>
                            <dd class="mt-1 font-semibold text-navy-900">{{ $certificate->course->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Issue date</dt>
                            <dd class="mt-1 font-semibold text-navy-900">{{ $certificate->issue_date->format('d F Y') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-8">
                        <a href="{{ route('public.certificates.print', $certificate) }}" target="_blank" class="btn-primary px-6 py-3">
                            View Certificate
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection