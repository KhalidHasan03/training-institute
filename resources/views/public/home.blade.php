@extends('layouts.public')

@section('title', 'Home')

@section('content')
    {{-- HERO BANNER --}}
    <section class="relative overflow-hidden bg-navy-950">
        {{-- Aurora background --}}
        <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-70"></div>
        <div class="pointer-events-none absolute -left-52 -top-40 h-[34rem] w-[34rem] animate-aurora rounded-full bg-gradient-to-br from-brand-600/50 to-brand-400/30 blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10rem] top-[-6rem] h-[30rem] w-[30rem] animate-aurora rounded-full bg-gradient-to-br from-accent-600/40 to-brand-500/30 blur-3xl" style="animation-delay: -6s"></div>
        <div class="pointer-events-none absolute bottom-[-12rem] left-1/3 h-[26rem] w-[26rem] animate-aurora rounded-full bg-gradient-to-br from-navy-500/50 to-brand-700/40 blur-3xl" style="animation-delay: -12s"></div>
        <div class="pointer-events-none absolute top-1/2 right-1/4 h-[20rem] w-[20rem] animate-float-slow rounded-full bg-white/5 blur-2xl"></div>

        <div class="relative mx-auto grid max-w-7xl gap-14 px-4 pb-24 pt-20 sm:px-6 lg:grid-cols-2 lg:items-center lg:pb-32 lg:pt-28 lg:px-8">
            <div class="relative">
                <div class="reveal reveal-visible">
                    <span class="eyebrow-dark">
                        <span class="h-1.5 w-1.5 rounded-full bg-accent-400 animate-blink"></span>
                        New batch enrolling now
                    </span>
                    <h1 class="mt-6 font-display text-4xl font-extrabold leading-[1.1] text-white sm:text-5xl lg:text-6xl">
                        Master skills that<br>
                        <span class="text-gradient">get you hired.</span>
                    </h1>
                    <p class="mt-6 max-w-lg text-lg leading-relaxed text-slate-300">
                        Hands-on, instructor-led training in web development, design, data science and more. Learn by building real projects — and graduate with a verified certificate.
                    </p>
                    <div class="mt-9 flex flex-wrap gap-4">
                        <a href="{{ route('public.courses') }}"
                           class="group relative inline-flex items-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-brand-600 to-accent-600 px-7 py-3.5 text-base font-semibold text-white shadow-glow-lg transition-all duration-200 hover:shadow-glow-lg hover:brightness-110">
                            <span class="pointer-events-none absolute inset-0 animate-shine bg-gradient-to-r from-transparent via-white/30 to-transparent"></span>
                            Explore Courses
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 transition-transform duration-200 group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-7 py-3.5 text-base font-semibold text-white backdrop-blur transition-colors hover:border-white/40 hover:bg-white/10">
                            Start Learning Free
                        </a>
                    </div>
                </div>

                {{-- Animated stats --}}
                <div class="mt-12 grid max-w-lg grid-cols-3 gap-6">
                    <div class="reveal reveal-visible reveal-delay-1">
                        <p class="font-display text-3xl font-extrabold text-white" data-count="{{ $stats['students'] }}">0</p>
                        <p class="mt-1 text-sm text-slate-400">Students trained</p>
                        <div class="mt-2 h-0.5 w-8 rounded-full bg-gradient-to-r from-brand-500 to-accent-500"></div>
                    </div>
                    <div class="reveal reveal-visible reveal-delay-2">
                        <p class="font-display text-3xl font-extrabold text-white" data-count="{{ $stats['courses'] }}">0</p>
                        <p class="mt-1 text-sm text-slate-400">Active courses</p>
                        <div class="mt-2 h-0.5 w-8 rounded-full bg-gradient-to-r from-brand-500 to-accent-500"></div>
                    </div>
                    <div class="reveal reveal-visible reveal-delay-3">
                        <p class="font-display text-3xl font-extrabold text-white" data-count="{{ $stats['trainers'] }}">0</p>
                        <p class="mt-1 text-sm text-slate-400">Expert trainers</p>
                        <div class="mt-2 h-0.5 w-8 rounded-full bg-gradient-to-r from-brand-500 to-accent-500"></div>
                    </div>
                </div>
            </div>

            <div class="relative hidden lg:block">
                @if ($spotlight)
                    <div class="reveal reveal-visible reveal-delay-2 relative">
                        <div class="absolute -inset-3 rounded-3xl bg-gradient-to-br from-brand-500/40 to-accent-500/40 blur-2xl animate-pulse-glow"></div>
                        <div class="relative overflow-hidden rounded-3xl border border-white/15 bg-white/90 p-6 shadow-2xl backdrop-blur-xl">
                            <div class="pointer-events-none absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-500 via-accent-500 to-brand-500 animate-pulse-glow"></div>

                            <div class="flex items-center gap-3 border-b border-slate-200/70 pb-4">
                                <span class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-full bg-gradient-to-br from-brand-100 to-accent-100 ring-2 ring-brand-500/30">
                                    @if ($spotlight['student']->photo)
                                        <img src="{{ Storage::url($spotlight['student']->photo) }}" alt="{{ $spotlight['student']->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="font-display text-sm font-bold text-brand-700">{{ strtoupper(substr($spotlight['student']->name, 0, 1)) }}</span>
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-navy-900">{{ $spotlight['student']->name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $spotlight['course']->title }} · Batch {{ $spotlight['batch']->name }}</p>
                                </div>
                                <span class="ml-auto h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>

                            <div class="space-y-5 py-5">
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="font-medium text-slate-600">Course Progress</span>
                                        <span class="font-bold text-gradient">{{ $spotlight['progress'] }}%</span>
                                    </div>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200/70">
                                        <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-accent-500 transition-all duration-1000" style="width: {{ $spotlight['progress'] }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="font-medium text-slate-600">Attendance</span>
                                        <span class="font-bold text-emerald-600">{{ $spotlight['attendance'] }}%</span>
                                    </div>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200/70">
                                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-1000" style="width: {{ $spotlight['attendance'] }}%"></div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-brand-100 bg-gradient-to-br from-brand-50 to-accent-50 p-4">
                                    <p class="text-xs font-medium text-brand-600">Next Class</p>
                                    @if ($spotlight['nextClass'])
                                        <p class="mt-1 text-sm font-bold text-navy-900">{{ $spotlight['nextClass']->topic ?? $spotlight['course']->title }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $spotlight['nextClass']->date->format('D, d M') }} · {{ $spotlight['nextClass']->start_time?->format('g:i A') }} – {{ $spotlight['nextClass']->end_time?->format('g:i A') }}
                                            @if ($spotlight['nextClass']->room) · Room {{ $spotlight['nextClass']->room }}@endif
                                        </p>
                                    @else
                                        <p class="mt-1 text-sm font-bold text-navy-900">Classes starting soon</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="reveal reveal-visible reveal-delay-2 relative">
                        <div class="absolute -inset-3 rounded-3xl bg-gradient-to-br from-brand-500/40 to-accent-500/40 blur-2xl animate-pulse-glow"></div>
                        <div class="relative rounded-3xl border border-white/15 bg-white/90 p-10 text-center shadow-2xl backdrop-blur-xl">
                            <h3 class="font-display text-2xl font-bold text-navy-900">Your next career move starts here</h3>
                            <p class="mx-auto mt-3 max-w-xs text-sm text-slate-500">
                                Join a hands-on program today and graduate with a verified certificate.
                            </p>
                            <a href="{{ route('register') }}" class="btn-primary mt-7 w-full py-3.5">Start Learning Free</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Bottom gradient divider --}}
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent"></div>
    </section>

    {{-- TRUSTED MARQUEE --}}
    <div class="overflow-hidden border-b border-slate-200/80 bg-white py-5 dark:border-white/10 dark:bg-navy-950">
        <div class="flex w-max animate-marquee gap-12 whitespace-nowrap">
            @foreach ([
                ['Web Development', 'code-bracket'],
                ['UI / UX Design', 'paint-brush'],
                ['Data Science', 'chart-bar'],
                ['Digital Marketing', 'megaphone'],
                ['Mobile Apps', 'device-phone'],
                ['Career Support', 'briefcase'],
            ] as $i => [$label, $_icon])
                <span class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                    <span class="h-1.5 w-1.5 rotate-45 rounded-[2px] bg-gradient-to-br from-brand-500 to-accent-500"></span>
                    {{ $label }}
                </span>
            @endforeach
            @foreach ([
                ['Web Development', 'code-bracket'],
                ['UI / UX Design', 'paint-brush'],
                ['Data Science', 'chart-bar'],
                ['Digital Marketing', 'megaphone'],
                ['Mobile Apps', 'device-phone'],
                ['Career Support', 'briefcase'],
            ] as $i => [$label, $_icon])
                <span class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                    <span class="h-1.5 w-1.5 rotate-45 rounded-[2px] bg-gradient-to-br from-brand-500 to-accent-500"></span>
                    {{ $label }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- FEATURED COURSES --}}
    @if ($featuredCourses->count())
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="reveal flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="eyebrow">Featured Courses</span>
                    <h2 class="mt-4 max-w-xl font-display text-3xl font-bold text-navy-900 sm:text-4xl dark:text-white">Popular programs to <span class="text-gradient">level up</span></h2>
                </div>
                <a href="{{ route('public.courses') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-brand-600 transition-colors hover:text-accent-600">
                    View all courses
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
            <div class="mt-12 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredCourses as $course)
                    <div class="reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                        <x-public.course-card :course="$course" />
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- WHY CHOOSE US --}}
    <section class="relative overflow-hidden bg-navy-950 py-20 lg:py-24">
        <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
        <div class="pointer-events-none absolute -right-40 top-0 h-80 w-80 rounded-full bg-accent-600/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-40 bottom-0 h-80 w-80 rounded-full bg-brand-600/20 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="reveal mx-auto max-w-2xl text-center">
                <span class="eyebrow-dark">Why Choose Us</span>
                <h2 class="mt-4 font-display text-3xl font-bold text-white sm:text-4xl">A learning experience built <span class="text-gradient">around you</span></h2>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Hands-on Projects', 'Build real-world projects with every course and graduate with a portfolio.', 'M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z', 'brand'],
                    ['Expert Mentors', 'Learn directly from senior industry practitioners who work in the field daily.', 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z', 'accent'],
                    ['Verified Certificates', 'Earn a verifiable certificate for every course you complete successfully.', 'M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043A3.746 3.746 0 0 1 21 12Z', 'brand'],
                    ['Career Support', 'Get guidance on job hunting, interviews and building your professional profile.', 'M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z', 'accent'],
                ] as $idx => [$title, $text, $path, $tone])
                    <div class="reveal reveal-delay-{{ min($idx + 1, 4) }} group rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur transition-all duration-300 hover:-translate-y-1.5 hover:border-white/20 hover:bg-white/10">
                        <div class="relative grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-accent-600 text-white shadow-glow">
                            <span class="pointer-events-none absolute inset-0 rounded-2xl bg-white/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                            </svg>
                        </div>
                        <h3 class="mt-5 font-display text-lg font-bold text-white">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PREMIUM CTA --}}
    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
        <div class="reveal relative overflow-hidden rounded-[2.5rem] px-8 py-16 text-center shadow-glow-lg sm:px-16 lg:py-20">
            <div class="absolute inset-0 bg-brand-gradient"></div>
            <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
            <div class="pointer-events-none absolute -left-20 top-0 h-64 w-64 rounded-full bg-white/10 blur-3xl animate-float"></div>
            <div class="pointer-events-none absolute -bottom-10 right-0 h-64 w-64 rounded-full bg-white/10 blur-3xl animate-float-slow"></div>

            <div class="relative mx-auto max-w-2xl">
                <span class="eyebrow-dark">Get started</span>
                <h2 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-5xl">
                    Ready to start your journey?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-brand-100">
                    Join thousands of learners who transformed their careers with our instructor-led programs.
                </p>
                <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="btn-white group inline-flex items-center gap-2 px-8 py-3.5 text-base">
                        Enroll Now
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 transition-transform duration-200 group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                    </a>
                    <a href="{{ route('public.courses') }}" class="btn-ghost-light px-8 py-3.5 text-base">Browse Courses</a>
                </div>
            </div>
        </div>
    </section>
@endsection