<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Class Schedule</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name . ' · ' . $this->batch->course->title : 'No active batch' }}</p>
        </div>
        <div class="flex gap-1 rounded-xl border border-slate-200 bg-white p-1">
            <button wire:click="$set('tab', 'upcoming')"
                    class="rounded-lg px-4 py-1.5 text-sm font-medium transition-colors {{ $tab === 'upcoming' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">Upcoming</button>
            <button wire:click="$set('tab', 'past')"
                    class="rounded-lg px-4 py-1.5 text-sm font-medium transition-colors {{ $tab === 'past' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">Past</button>
        </div>
    </div>

    @if ($this->batch)
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card p-4">
                <p class="text-xs text-slate-400">Class days</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $this->batch->class_days }}</p>
            </div>
            <div class="card p-4">
                <p class="text-xs text-slate-400">Time</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $this->batch->start_time?->format('g:i A') }} – {{ $this->batch->end_time?->format('g:i A') }}</p>
            </div>
            <div class="card p-4">
                <p class="text-xs text-slate-400">Room</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $this->batch->room ?? '—' }}</p>
            </div>
            <div class="card p-4">
                <p class="text-xs text-slate-400">Trainer</p>
                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $this->batch->trainer?->name ?? 'TBA' }}</p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @forelse ($sessions as $session)
                <div class="card flex items-center gap-4 p-5 transition-shadow duration-200 hover:shadow-lift">
                    <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl {{ $session->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : ($session->status === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-brand-50 text-brand-600') }}">
                        <div class="text-center">
                            <p class="text-lg font-bold">{{ $session->date->format('d') }}</p>
                            <p class="text-[10px] font-semibold uppercase">{{ $session->date->format('M') }}</p>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="truncate text-sm font-semibold text-slate-800">{{ $session->topic ?? $this->batch->course->title }}</h4>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ $session->start_time?->format('g:i A') }} – {{ $session->end_time?->format('g:i A') }}
                            @if ($session->room) · {{ $session->room }} @endif
                            · {{ $session->date->format('D') }}
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <span class="badge {{ $session->status === 'completed' ? 'badge-green' : ($session->status === 'cancelled' ? 'badge-red' : 'badge-blue') }}">{{ ucfirst($session->status) }}</span>
                    </div>
                </div>
            @empty
                <x-student.empty-state
                    :title="$tab === 'upcoming' ? 'No upcoming classes' : 'No past classes'"
                    description="Your schedule for this period is empty. Check back soon." />
            @endforelse
        </div>
    @else
        <div class="mt-6">
            <x-student.empty-state title="No active enrollment" description="Enroll in a course to see your class schedule.">
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        </div>
    @endif
</div>