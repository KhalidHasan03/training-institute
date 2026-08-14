<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Results</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name : 'No active batch' }}</p>
        </div>
        @if ($this->batch && $results->count())
            <span class="badge badge-green">{{ $overall }}% overall</span>
        @endif
    </div>

    @if (! $this->batch)
        <div class="mt-6">
            <x-student.empty-state title="No active enrollment" description="Enroll in a course to track your exam performance.">
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        </div>
    @else
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($results as $result)
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-slate-800">{{ $result->exam->title }}</h4>
                        <span class="badge {{ match ($result->grade) { 'A+', 'A', 'B' => 'badge-green', 'C', 'D' => 'badge-amber', default => 'badge-red' } }}">{{ $result->grade }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">{{ $result->exam->exam_date->format('d M Y') }}</p>
                    <div class="mt-4">
                        <div class="flex items-baseline gap-2">
                            <p class="font-display text-2xl font-bold text-slate-900">{{ number_format((float) $result->marks) }}<span class="text-sm font-medium text-slate-400"> / {{ number_format((float) $result->exam->total_marks) }}</span></p>
                            <span class="text-sm font-semibold text-brand-600">{{ $result->percentage }}%</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-brand-500" style="width: {{ $result->percentage }}%"></div>
                        </div>
                    </div>
                    @if ($result->remarks)
                        <p class="mt-3 text-xs text-slate-500">{{ $result->remarks }}</p>
                    @endif
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3">
                    <x-student.empty-state title="No results yet" description="Your exam results will appear here once published by your trainer." />
                </div>
            @endforelse
        </div>
    @endif
</div>
