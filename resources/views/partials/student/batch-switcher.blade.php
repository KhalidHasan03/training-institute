@php
    $student = auth()->user()->student;
    $enrollments = $student?->enrollments()
        ->with('batch.course')
        ->where('status', 'active')
        ->orderBy('enrollment_date')
        ->get() ?? collect();

    $currentBatchId = (int) (request()->query('batch') ?: session('student_batch_id'));
    $currentEnrollment = $enrollments->firstWhere('batch_id', $currentBatchId) ?? $enrollments->first();
@endphp

@if ($enrollments->count() > 0)
    <div class="flex items-center gap-2" x-data="{ open: false }">
        <span class="hidden text-xs font-medium text-slate-400 sm:block">Batch</span>
        <div class="relative">
            <button @click="open = !open" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:border-brand-300 hover:text-brand-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-brand-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
                <span>{{ $currentEnrollment?->batch->name ?? 'Select batch' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 z-50 mt-2 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-lift">
                @foreach ($enrollments as $enrollment)
                    <a href="{{ url()->current() }}?batch={{ $enrollment->batch_id }}"
                       class="flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors {{ $enrollment->batch_id === $currentEnrollment?->batch_id ? 'bg-brand-50 text-brand-800' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="min-w-0">
                            <span class="block truncate font-semibold">{{ $enrollment->batch->course->title }}</span>
                            <span class="block text-xs text-slate-400">Batch {{ $enrollment->batch->name }}</span>
                        </span>
                        @if ($enrollment->batch_id === $currentEnrollment?->batch_id)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4 shrink-0 text-brand-600"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
