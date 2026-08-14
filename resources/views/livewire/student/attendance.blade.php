<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Attendance</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name : 'No active batch' }}</p>
        </div>
        <span class="badge {{ $this->attendancePercentage >= 75 ? 'badge-green' : 'badge-amber' }}">{{ $this->attendancePercentage }}% attendance</span>
    </div>

    @if ($this->batch)
        <div class="mt-6 grid grid-cols-3 gap-4">
            <div class="card p-5 text-center">
                <p class="font-display text-2xl font-bold text-emerald-600">{{ $present }}</p>
                <p class="mt-1 text-xs text-slate-500">Present</p>
            </div>
            <div class="card p-5 text-center">
                <p class="font-display text-2xl font-bold text-amber-600">{{ $late }}</p>
                <p class="mt-1 text-xs text-slate-500">Late</p>
            </div>
            <div class="card p-5 text-center">
                <p class="font-display text-2xl font-bold text-red-600">{{ $absent }}</p>
                <p class="mt-1 text-xs text-slate-500">Absent</p>
            </div>
        </div>

        <div class="card mt-6 overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="font-display text-base font-bold text-slate-900">Attendance History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Date</th>
                            <th class="px-6 py-3 font-semibold">Class</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $record)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3.5 font-medium text-slate-700">{{ $record->date->format('d M Y') }} <span class="text-slate-400">({{ $record->date->format('D') }})</span></td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $record->classSession?->topic ?? 'Class session' }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="badge {{ $record->status === 'present' ? 'badge-green' : ($record->status === 'late' ? 'badge-amber' : 'badge-red') }}">{{ ucfirst($record->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-10 text-center text-slate-400">No attendance records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-6">
            <x-student.empty-state title="No active enrollment" description="Enroll in a course to track your attendance.">
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        </div>
    @endif
</div>