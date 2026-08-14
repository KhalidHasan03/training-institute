@extends('layouts.public')

@section('title', 'Sign in')

@section('content')
    <section class="relative overflow-hidden bg-navy-950 pb-28 pt-16 sm:pt-20">
        <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
        <div class="pointer-events-none absolute -left-40 -top-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-brand-600/40 to-brand-400/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 -bottom-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-accent-600/40 to-brand-500/20 blur-3xl" style="animation-delay: -9s"></div>
        <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <span class="eyebrow-dark reveal">Student & Trainer Portal</span>
            <h1 class="reveal reveal-delay-1 mt-5 font-display text-4xl font-extrabold text-white sm:text-5xl">Welcome <span class="text-gradient">back</span></h1>
            <p class="reveal reveal-delay-2 mx-auto mt-4 max-w-xl text-lg text-slate-300">Sign in to continue to your dashboard.</p>
        </div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent"></div>
    </section>

    <section class="mx-auto -mt-14 max-w-md px-4">
        <div class="reveal relative">
            <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-brand-500/25 to-accent-500/25 blur-2xl"></div>
            <div class="card relative !rounded-3xl p-8 shadow-lift">
                @if ($errors->any())
                    <div class="mb-5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-600/20">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="label" for="email">Email address</label>
                        <input class="input" id="email" name="email" type="email" value="{{ old('email') }}" autofocus required>
                    </div>
                    <div>
                        <label class="label" for="password">Password</label>
                        <input class="input" id="password" name="password" type="password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            Remember me
                        </label>
                    </div>
                    <button type="submit" class="btn-primary w-full py-3">Sign in</button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    New here?
                    <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700">Create an account</a>
                </p>
            </div>
        </div>
    </section>
@endsection