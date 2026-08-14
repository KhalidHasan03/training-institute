@extends('layouts.public')

@section('title', 'Create account')

@section('content')
    <section class="relative overflow-hidden bg-navy-950 pb-28 pt-16 sm:pt-20">
        <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
        <div class="pointer-events-none absolute -left-40 -top-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-brand-600/40 to-brand-400/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 -bottom-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-accent-600/40 to-brand-500/20 blur-3xl" style="animation-delay: -9s"></div>
        <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <span class="eyebrow-dark reveal">Join the community</span>
            <h1 class="reveal reveal-delay-1 mt-5 font-display text-4xl font-extrabold text-white sm:text-5xl">Create your <span class="text-gradient">account</span></h1>
            @if ($batch ?? null)
                <p class="reveal reveal-delay-2 mx-auto mt-4 max-w-xl text-lg text-slate-300">Create your account to enroll in <span class="font-semibold text-white">{{ $batch?->course?->title }}</span> — Batch {{ $batch?->name }}.</p>
            @else
                <p class="reveal reveal-delay-2 mx-auto mt-4 max-w-xl text-lg text-slate-300">Join as a student and start learning today.</p>
            @endif
        </div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent"></div>
    </section>

    <section class="mx-auto -mt-14 max-w-md px-4">
        <div class="reveal relative">
            <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-brand-500/25 to-accent-500/25 blur-2xl"></div>
            <div class="card relative !rounded-3xl p-8 shadow-lift">
                @if ($batch ?? null)
                    <div class="mb-5 rounded-xl border border-brand-100 bg-brand-50/70 px-4 py-3 text-sm text-brand-800">
                        <p class="font-semibold">{{ $batch?->course?->title }}</p>
                        <p class="mt-0.5 text-xs text-brand-600">Batch {{ $batch?->name }} · {{ $batch?->start_date?->format('d M Y') }} – {{ $batch?->end_date?->format('d M Y') }}</p>
                        <p class="mt-0.5 text-xs text-brand-600">You'll be enrolled in this batch right after your account is created.</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-600/20">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    @if ($batch ?? null)
                        <input type="hidden" name="batch" value="{{ $batch->id }}">
                    @endif
                    <div>
                        <label class="label" for="name">Full name</label>
                        <input class="input" id="name" name="name" value="{{ old('name') }}" autofocus required>
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="email">Email address</label>
                        <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" required>
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="phone">Phone <span class="text-slate-400">(optional)</span></label>
                        <input class="input" id="phone" name="phone" type="tel" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label class="label" for="password">Password</label>
                        <input class="input" id="password" name="password" type="password" required>
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="password_confirmation">Confirm password</label>
                        <input class="input" id="password_confirmation" name="password_confirmation" type="password" required>
                    </div>
                    <button type="submit" class="btn-primary w-full py-3">Create account</button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">Sign in</a>
                </p>
            </div>
        </div>
    </section>
@endsection