@extends('layouts.public')

@section('title', 'About Us')

@section('content')
    <x-public.page-hero
        :eyebrow="'About Us'"
        :title="'Shaping careers through <span class=&quot;text-gradient&quot;>practical education</span>'"
        subtitle="We are a modern training institute focused on one thing: helping you gain skills that translate directly into career opportunities."
        :items="[
            [$stats['students'] ?? 0, 'Students trained'],
            [$stats['courses'] ?? 0, 'Active courses'],
            [$stats['trainers'] ?? 0, 'Expert trainers'],
            [$stats['completion_rate'] ?? 0, 'Completion rate'],
        ]"
        :stat-suffixes="['', '', '', '%']"
    />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2">
            <div class="reveal space-y-6">
                <span class="eyebrow">Our mission</span>
                <h2 class="font-display text-3xl font-bold text-navy-900 sm:text-4xl dark:text-white">Skills that ship, <span class="text-gradient">not just slides</span></h2>
                <p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    Technology moves fast, and most educational content falls behind. We keep our curriculum current, project-based and taught by people who actually build software, design products and analyze data professionally.
                </p>
                <p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    Every course ends with a real project and a verified certificate, so when you graduate, the whole world can verify what you have accomplished.
                </p>

                <div class="grid grid-cols-2 gap-5 pt-2">
                    <div class="rounded-2xl border border-brand-100 bg-gradient-to-br from-brand-50 to-accent-50 p-6 shadow-soft dark:border-brand-400/20 dark:from-brand-500/10 dark:to-accent-500/10">
                        <p class="font-display text-3xl font-extrabold text-gradient" data-count="{{ $stats['students'] }}">0</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Students trained</p>
                    </div>
                    <div class="rounded-2xl border border-brand-100 bg-gradient-to-br from-brand-50 to-accent-50 p-6 shadow-soft dark:border-brand-400/20 dark:from-brand-500/10 dark:to-accent-500/10">
                        <p class="font-display text-3xl font-extrabold text-gradient">{{ $stats['courses'] }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Active courses</p>
                    </div>
                </div>
            </div>

            <div class="reveal reveal-delay-1 relative">
                <div class="absolute -inset-2 rounded-3xl bg-gradient-to-br from-brand-500/25 to-accent-500/25 blur-2xl"></div>
                <div class="relative rounded-3xl border border-brand-100 bg-white p-8 shadow-lift lg:p-10 dark:border-white/10 dark:bg-navy-900/60 dark:shadow-none">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-accent-600 text-white shadow-glow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                        </svg>
                    </span>
                    <h3 class="mt-6 font-display text-2xl font-bold text-navy-900 dark:text-white">Our promise</h3>
                    <p class="mt-4 text-base leading-relaxed text-slate-600 dark:text-slate-400">
                        Small batches, personal mentorship, real projects and honest feedback. If you put in the work, we put in the effort to make sure you succeed.
                    </p>

                    <div class="mt-8 space-y-4">
                        @foreach ([
                            'Project-first curriculum updated with industry' => 'PencilSquare',
                            'Verified certificates anyone can check' => 'CheckBadge',
                            'Career coaching from working professionals' => 'Briefcase',
                        ] as $feature => $_icon)
                            <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                                <span class="grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-br from-brand-500 to-accent-600 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection