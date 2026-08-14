@extends('layouts.public')

@section('title', 'Verify Certificate')

@section('content')
    <x-public.page-hero
        :eyebrow="'Credential Check'"
        :title="'Verify a <span class=&quot;text-gradient&quot;>certificate</span>'"
        subtitle="Enter the certificate number printed on the credential to confirm its authenticity."
    />

    <section class="mx-auto max-w-lg px-4 py-16">
        <div class="reveal relative">
            <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-brand-500/20 to-accent-500/20 blur-2xl"></div>
            <div class="card relative !rounded-3xl p-8 shadow-lift">
                <form method="POST" action="{{ route('public.certificates.check') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="label" for="certificate_number">Certificate number</label>
                        <input class="input text-center tracking-widest" id="certificate_number" name="certificate_number" placeholder="CERT-2026-XXXXXXXX" required>
                    </div>
                    <button type="submit" class="btn-primary w-full py-3.5">Verify Certificate</button>
                </form>
            </div>
        </div>
    </section>
@endsection