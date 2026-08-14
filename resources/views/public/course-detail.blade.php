@extends('layouts.public')

@section('title', $course->title)

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-navy-950">
        <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
        <div class="pointer-events-none absolute -left-40 -top-32 h-80 w-80 animate-aurora rounded-full bg-gradient-to-br from-brand-600/40 to-brand-400/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 top-10 h-72 w-72 animate-aurora rounded-full bg-gradient-to-br from-accent-600/40 to-brand-500/20 blur-3xl" style="animation-delay: -9s"></div>

        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 pb-16 pt-12 sm:px-6 lg:grid-cols-3 lg:items-center lg:px-8 lg:pb-20">
            <div class="lg:col-span-2">
                <nav class="reveal flex items-center gap-2.5 text-sm text-slate-400">
                    <a href="{{ route('public.home') }}" class="transition-colors hover:text-white">Home</a>
                    <span class="text-slate-600 dark:text-white/30">/</span>
                    <a href="{{ route('public.courses') }}" class="transition-colors hover:text-white">Courses</a>
                    <span class="text-slate-600 dark:text-white/30">/</span>
                    <span class="text-slate-200">{{ $course->title }}</span>
                </nav>

                <div class="reveal reveal-delay-1">
                    <span class="eyebrow-dark mt-7">{{ ucfirst($course->level) }} Program</span>
                    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">{{ $course->title }}</h1>
                    <p class="mt-5 max-w-2xl text-lg leading-relaxed text-slate-300">{{ $course->short_description }}</p>
                </div>

                <div class="reveal reveal-delay-2 mt-7 flex flex-wrap items-center gap-x-8 gap-y-3 text-sm text-slate-300">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-brand-400"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <span class="font-semibold text-white">{{ $course->duration }}</span> duration
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-brand-400"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" /></svg>
                        <span class="font-semibold text-white">{{ ucfirst($course->level) }}</span> level
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-brand-400"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                        <span class="font-semibold text-white">{{ $course->activeBatches->count() }}</span> open batches
                    </span>
                </div>
            </div>

            <div class="reveal reveal-delay-2 lg:col-span-1">
                <div class="relative">
                    <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-brand-500/40 to-accent-500/40 blur-2xl animate-pulse-glow"></div>
                    <div class="relative overflow-hidden rounded-3xl border border-brand-500/20 bg-white shadow-2xl dark:border-white/10 dark:bg-navy-900/80">
                        <div class="aspect-[16/10] bg-gradient-to-br from-navy-900 via-brand-900 to-accent-800">
                            @if ($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-end justify-between">
                                <div class="flex items-baseline gap-1">
                                    <p class="font-display text-3xl font-extrabold text-gradient">৳{{ number_format($course->fee) }}</p>
                                    <p class="text-sm text-slate-400">/ course</p>
                                </div>
                            </div>
                            <a href="#batches" class="btn-primary mt-5 w-full py-3.5 text-base">
                                Enroll Now
                            </a>
                            <a href="{{ route('register') }}" class="btn-outline mt-3 w-full py-3">Create Free Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-brand-500/50 to-transparent"></div>
    </section>

    {{-- Body --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-3">
            <div class="space-y-12 lg:col-span-2">
                <div class="reveal">
                    <span class="eyebrow">About this course</span>
                    <h2 class="mt-3 font-display text-2xl font-bold text-navy-900 sm:text-3xl dark:text-white">Course overview</h2>
                    <div class="prose mt-6 max-w-none text-slate-600 dark:text-slate-400">
                        {!! $course->description !!}
                    </div>
                </div>

                <div class="reveal">
                    <span class="eyebrow">Curriculum</span>
                    <h2 class="mt-3 font-display text-2xl font-bold text-navy-900 sm:text-3xl dark:text-white">What you will learn</h2>
                    <div class="mt-7 grid gap-4 sm:grid-cols-2">
                        @forelse ($course->highlights ?? [] as $item)
                            <div class="group flex items-start gap-3.5 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 transition-all duration-200 hover:border-brand-300/70 hover:bg-gradient-to-br hover:from-brand-50 hover:to-accent-50 hover:shadow-soft dark:border-white/10 dark:bg-white/5 dark:hover:border-brand-400/30 dark:hover:from-brand-500/10 dark:hover:to-accent-500/10">
                                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-soft transition-transform duration-200 group-hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium leading-relaxed text-slate-700 dark:text-slate-300">{{ $item }}</span>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/60 p-6 text-sm text-slate-500 sm:col-span-2 dark:border-white/15 dark:bg-white/5 dark:text-slate-400">
                                A detailed curriculum is being prepared for this course.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="reveal card !rounded-3xl p-7">
                    <h3 class="font-display text-base font-bold text-navy-900 dark:text-white">Course summary</h3>
                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between rounded-xl bg-slate-50/70 px-4 py-3 dark:bg-white/5"><dt class="text-slate-500 dark:text-slate-400">Duration</dt><dd class="font-semibold text-navy-900 dark:text-white">{{ $course->duration }}</dd></div>
                        <div class="flex justify-between rounded-xl bg-slate-50/70 px-4 py-3 dark:bg-white/5"><dt class="text-slate-500 dark:text-slate-400">Level</dt><dd class="font-semibold text-navy-900 dark:text-white">{{ ucfirst($course->level) }}</dd></div>
                        <div class="flex justify-between rounded-xl bg-slate-50/70 px-4 py-3 dark:bg-white/5"><dt class="text-slate-500 dark:text-slate-400">Fee</dt><dd class="font-semibold text-gradient">৳{{ number_format($course->fee) }}</dd></div>
                        <div class="flex justify-between rounded-xl bg-slate-50/70 px-4 py-3 dark:bg-white/5"><dt class="text-slate-500 dark:text-slate-400">Open batches</dt><dd class="font-semibold text-navy-900 dark:text-white">{{ $course->activeBatches->count() }}</dd></div>
                        <div class="flex justify-between rounded-xl bg-slate-50/70 px-4 py-3 dark:bg-white/5"><dt class="text-slate-500 dark:text-slate-400">Certificate</dt><dd class="font-semibold text-emerald-600">Included</dd></div>
                    </dl>
                </div>

                <div class="reveal reveal-delay-1 relative overflow-hidden rounded-3xl bg-brand-gradient p-7 text-white shadow-glow-lg">
                    <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-50"></div>
                    <span class="relative grid h-10 w-10 place-items-center rounded-xl bg-white/15 backdrop-blur">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </span>
                    <h3 class="relative mt-4 font-display text-lg font-bold">Need help choosing?</h3>
                    <p class="relative mt-2 text-sm text-brand-100">Talk to our advisors about the right program for your goals.</p>
                    <a href="{{ route('public.contact') }}" class="relative mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-brand-700 transition-colors hover:bg-brand-50">
                        Contact us →
                    </a>
                </div>
            </aside>
        </div>
    </section>

    {{-- Batches --}}
    <section id="batches" class="border-t border-slate-200/80 bg-slate-50/80 py-16 dark:border-white/10 dark:bg-navy-950/60">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="reveal max-w-xl">
                <span class="eyebrow">Schedules</span>
                <h2 class="mt-3 font-display text-2xl font-bold text-navy-900 sm:text-3xl dark:text-white">Open batches</h2>
                <p class="mt-2 text-slate-500 dark:text-slate-400">Pick the batch that fits your schedule and enroll.</p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-2">
                @forelse ($course->activeBatches as $batch)
                    <div class="reveal reveal-delay-{{ min($loop->iteration % 4, 4) }} card !rounded-3xl p-7 transition-all duration-200 hover:border-brand-300/70 hover:shadow-glow dark:hover:border-brand-400/40">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-brand-600 to-accent-600 px-3 py-1 text-xs font-semibold text-white shadow-glow">Batch {{ $batch->name }}</span>
                                <h3 class="mt-3 font-display text-lg font-bold text-navy-900 dark:text-white">{{ $batch->name }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $batch->start_date->format('d M Y') }} – {{ $batch->end_date->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400">Seats</p>
                                <p class="font-display text-lg font-extrabold {{ $batch->capacity_reached ? 'text-red-600 dark:text-red-400' : 'text-brand-700 dark:text-brand-400' }}">
                                    {{ $batch->active_enrollments_count }}/{{ $batch->max_students }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-2 gap-3 border-t border-slate-100 pt-6 text-sm dark:border-white/10">
                            <div>
                                <p class="text-xs text-slate-400">Class days</p>
                                <p class="mt-0.5 font-medium text-navy-900 dark:text-white">{{ $batch->class_days }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Time</p>
                                <p class="mt-0.5 font-medium text-navy-900 dark:text-white">
                                    {{ optional($batch->start_time)->format('g:i A') }} – {{ optional($batch->end_time)->format('g:i A') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Trainer</p>
                                <p class="mt-0.5 font-medium text-navy-900 dark:text-white">{{ $batch->trainer?->name ?? 'TBA' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Room</p>
                                <p class="mt-0.5 font-medium text-navy-900 dark:text-white">{{ $batch->room ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            @auth
                                @if (auth()->user()->isStudent())
                                    @if (in_array($batch->id, $enrolledBatchIds ?? []))
                                        <span class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/30">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                            Enrolled
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('student.enroll', $batch) }}" class="w-full">
                                            @csrf
                                            <button type="submit" class="btn-primary group w-full">
                                                Enroll in this batch
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('public.courses.show', $course->slug) }}#batches" class="btn-secondary w-full">Sign in as a student to enroll</a>
                                @endif
                            @else
                                <a href="{{ route('register', ['batch' => $batch->id]) }}" class="btn-primary w-full group">
                                    Enroll Now
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="lg:col-span-2">
                        <div class="reveal card p-12 text-center !rounded-3xl">
                            <p class="font-display text-lg font-semibold text-navy-900 dark:text-white">No open batches right now</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Enroll to be notified when the next batch opens.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection