<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Attendance</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name . ' · ' . $this->batch->course->title : 'No batch selected' }}</p>
        </div>
        <span class="badge {{ $summary['present'] >= $summary['absent'] ? 'badge-green' : 'badge-amber' }}">{{ $summary['present'] }} present · {{ $summary['late'] }} late · {{ $summary['absent'] }} absent</span>
    </div>

    @if (session()->has('trainer-attendance-saved'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">{{ session('trainer-attendance-saved') }}</div>
    @endif

    @if (session()->has('trainer-attendance-error'))
        <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">{{ session('trainer-attendance-error') }}</div>
    @endif

    @if (! $this->batch)
        <div class="mt-6">
            <x-trainer.empty-state title="No batch selected" description="Use the batch switcher in the top bar to pick a batch and record attendance." />
        </div>
    @else
        <div class="mt-6 grid grid-cols-3 gap-4">
            <div class="card p-5 text-center">
                <p class="font-display text-2xl font-bold text-emerald-600">{{ $summary['present'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Present</p>
            </div>
            <div class="card p-5 text-center">
                <p class="font-display text-2xl font-bold text-amber-600">{{ $summary['late'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Late</p>
            </div>
            <div class="card p-5 text-center">
                <p class="font-display text-2xl font-bold text-red-600">{{ $summary['absent'] }}</p>
                <p class="mt-1 text-xs text-slate-500">Absent</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            {{-- Mark attendance --}}
            <div class="space-y-6 lg:col-span-2">
                <div class="card overflow-hidden">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h3 class="font-display text-base font-bold text-slate-900">Mark Attendance</h3>
                        <p class="mt-1 text-sm text-slate-500">Pick a session, then set each student's status.</p>
                    </div>
                    <div class="p-6">
                        @if ($sessions->isEmpty())
                            <p class="rounded-xl bg-slate-50 px-4 py-6 text-center text-sm text-slate-400">No sessions available for this batch yet. <a href="{{ route('trainer.sessions') }}" class="font-semibold text-brand-600">Create one →</a></p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach ($sessions as $session)
                                    <button wire:click="selectSession({{ $session->id }})"
                                            class="rounded-xl border px-3 py-2 text-xs font-medium transition-colors {{ $sessionId === $session->id ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-slate-200 text-slate-600 hover:border-brand-300' }}">
                                        {{ $session->date->format('d M') }} · {{ $session->start_time?->format('g:i') }}
                                        <span class="block text-[10px] text-slate-400">{{ Str::limit($session->topic ?? $this->batch->course->title, 18) }}</span>
                                    </button>
                                @endforeach
                            </div>

                            @if ($this->session)
                                <form wire:submit="saveAttendance" class="mt-6">
                                    <div class="flex items-center justify-between rounded-xl bg-brand-50/60 px-4 py-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $this->session->topic ?? $this->batch->course->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $this->session->date->format('l, d M Y') }} · {{ $this->session->start_time?->format('g:i A') }} – {{ $this->session->end_time?->format('g:i A') }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200">
                                        <table class="w-full text-left text-sm">
                                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                                <tr>
                                                    <th class="px-4 py-3 font-semibold">Student</th>
                                                    <th class="px-4 py-3 font-semibold">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @forelse ($this->enrolledStudents as $student)
                                                    <tr class="hover:bg-slate-50">
                                                        <td class="px-4 py-3">
                                                            <p class="font-medium text-slate-800">{{ $student->name }}</p>
                                                            <p class="text-xs text-slate-400">{{ $student->student_id }}</p>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex gap-1.5">
                                                                @foreach (['present' => 'P', 'late' => 'L', 'absent' => 'A'] as $value => $label)
                                                                    <label class="cursor-pointer">
                                                                        <input type="radio" name="status_{{ $student->id }}" value="{{ $value }}" wire:model="statuses.{{ $student->id }}" class="peer hidden">
                                                                        <span class="grid h-8 w-8 place-items-center rounded-lg border text-xs font-bold transition-colors {{ ($statuses[(string) $student->id] ?? 'present') === $value ? ($value === 'present' ? 'border-emerald-500 bg-emerald-500 text-white' : ($value === 'late' ? 'border-amber-500 bg-amber-500 text-white' : 'border-red-500 bg-red-500 text-white')) : 'border-slate-200 text-slate-500 peer-hover:border-slate-400' }}">{{ $label }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="2" class="px-4 py-10 text-center text-slate-400">No students enrolled in this batch yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    @if ($this->enrolledStudents->isNotEmpty())
                                        <div class="mt-4 flex items-center gap-3">
                                            <button type="submit" class="btn-primary">Save Attendance</button>
                                            <button type="button" wire:click="$refresh" class="btn-secondary">Refresh</button>
                                        </div>
                                    @endif
                                </form>
                            @else
                                <p class="mt-6 rounded-xl bg-slate-50 px-4 py-6 text-center text-sm text-slate-400">Select a session above to mark attendance.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- History --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="font-display text-base font-bold text-slate-900">History</h3>
                    <div class="flex gap-1 rounded-lg border border-slate-200 bg-white p-0.5">
                        @foreach (['all' => 'All', 'today' => 'Today', 'week' => 'Week', 'month' => 'Month'] as $key => $label)
                            <button wire:click="$set('filterDate', '{{ $key }}')"
                                    class="rounded-md px-2 py-1 text-xs font-medium transition-colors {{ $filterDate === $key ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="max-h-[34rem] overflow-y-auto">
                    @forelse ($history as $record)
                        <div class="flex items-center gap-3 border-b border-slate-50 px-6 py-3">
                            <span class="badge {{ $record->status === 'present' ? 'badge-green' : ($record->status === 'late' ? 'badge-amber' : 'badge-red') }}">{{ ucfirst($record->status) }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $record->student->name }}</p>
                                <p class="text-xs text-slate-400">{{ $record->date->format('d M Y') }} · {{ $record->classSession?->topic ? Str::limit($record->classSession->topic, 22) : 'Session' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-slate-400">No attendance records for this period.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>