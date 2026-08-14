@props(['course'])

<a href="{{ route('public.courses.show', $course->slug) }}" class="group card relative flex flex-col overflow-hidden !rounded-3xl transition-all duration-300 hover:-translate-y-1.5 hover:border-brand-300/70 hover:shadow-glow dark:hover:border-brand-400/40">
    <span class="pointer-events-none absolute inset-x-0 top-0 z-10 h-0.5 bg-gradient-to-r from-brand-500 via-accent-500 to-brand-500 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>

    <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-navy-900 via-brand-900 to-accent-800">
        @if ($course->thumbnail)
            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="pointer-events-none absolute inset-0 bg-grid-lines opacity-60"></div>
            <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-accent-500/30 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-brand-500/30 blur-2xl"></div>
            <div class="relative flex h-full w-full items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-12 w-12 text-white/30">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
        @endif
        <span class="absolute left-3 top-3 badge backdrop-blur {{ $course->level === 'beginner' ? 'bg-emerald-500/90 text-white' : ($course->level === 'intermediate' ? 'bg-brand-500/90 text-white' : 'bg-accent-500/90 text-white') }}">
            {{ ucfirst($course->level) }}
        </span>
    </div>

    <div class="flex flex-1 flex-col p-6">
        <h3 class="font-display text-lg font-bold text-navy-900 transition-colors duration-200 group-hover:text-brand-700 dark:text-white dark:group-hover:text-brand-300">{{ $course->title }}</h3>
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-500">{{ $course->short_description }}</p>

        <div class="mt-5 flex items-center gap-4 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-brand-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                {{ $course->duration }}
            </span>
            <span class="inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-brand-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 0 4.5 6h15a.75.75 0 0 0 .75-.75v-.75m-16.5 0v.75a.75.75 0 0 0 .75.75h15a.75.75 0 0 0 .75-.75v-.75m-16.5 0A.75.75 0 0 1 4.5 3h15a.75.75 0 0 1 .75.75v.75m-16.5 0A.75.75 0 0 1 4.5 3h15a.75.75 0 0 1 .75.75v.75m-16.5 0A.75.75 0 0 1 4.5 3h15a.75.75 0 0 1 .75.75v.75m-16.5 0A.75.75 0 0 1 4.5 3h15a.75.75 0 0 1 .75.75v.75" />
                </svg>
                {{ $course->active_batches_count ?? $course->activeBatches->count() }} batches
            </span>
        </div>

        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5 dark:border-white/10">
            <p class="font-display text-xl font-extrabold text-gradient">
                ৳{{ number_format($course->fee) }}
            </p>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3.5 py-1.5 text-sm font-semibold text-brand-700 transition-all duration-200 group-hover:gap-2.5 group-hover:bg-gradient-to-r group-hover:from-brand-600 group-hover:to-accent-600 group-hover:text-white dark:bg-brand-500/10 dark:text-brand-300">
                Details
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </span>
        </div>
    </div>
</a>