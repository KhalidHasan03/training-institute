<div>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">{{ $batch?->course->title ?? 'My Course' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Batch {{ $batch?->name ?? '—' }} · {{ $batch?->trainer?->name ?? 'TBA' }}</p>
        </div>
        @if ($enrollment)
            <div class="text-right">
                <p class="text-xs text-slate-400">Enrolled</p>
                <p class="text-sm font-semibold text-slate-700">{{ $enrollment->enrollment_date->format('d M Y') }}</p>
            </div>
        @endif
    </div>

    @if (! $batch)
        <div class="mt-6">
            <x-student.empty-state title="No active enrollment" description="Enroll in a course to view your class details here.">
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        </div>
    @else
        {{-- Tabs --}}
        <div class="mt-6 flex gap-1 overflow-x-auto rounded-2xl border border-slate-200 bg-white p-1.5">
            @foreach (['overview' => 'Overview', 'schedule' => 'Schedule', 'materials' => 'Materials', 'assignments' => 'Assignments', 'attendance' => 'Attendance', 'results' => 'Results'] as $key => $label)
                <button wire:click="$set('tab', '{{ $key }}')"
                        class="whitespace-nowrap rounded-xl px-4 py-2 text-sm font-medium transition-colors duration-150 {{ $tab === $key ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="mt-6">
            {{-- Overview --}}
            @if ($tab === 'overview')
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="card p-6">
                            <h3 class="font-display text-base font-bold text-slate-900">Course Progress</h3>
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">{{ $batch->start_date->format('d M Y') }}</span>
                                    <span class="font-bold text-brand-700">{{ $this->progress }}%</span>
                                    <span class="text-slate-500">{{ $batch->end_date->format('d M Y') }}</span>
                                </div>
                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600" style="width: {{ $this->progress }}%"></div>
                                </div>
                            </div>
                            <div class="mt-5 grid grid-cols-2 gap-4 rounded-xl bg-slate-50 p-4 text-sm sm:grid-cols-4">
                                <div>
                                    <p class="text-xs text-slate-400">Class days</p>
                                    <p class="mt-0.5 font-semibold text-slate-800">{{ $batch->class_days }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400">Time</p>
                                    <p class="mt-0.5 font-semibold text-slate-800">{{ $batch->start_time?->format('g:i A') }} – {{ $batch->end_time?->format('g:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400">Room</p>
                                    <p class="mt-0.5 font-semibold text-slate-800">{{ $batch->room ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400">Duration</p>
                                    <p class="mt-0.5 font-semibold text-slate-800">{{ $batch->course->duration }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-6">
                            <h3 class="font-display text-base font-bold text-slate-900">About this course</h3>
                            <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $batch->course->short_description }}</p>
                            <div class="mt-4 prose prose-sm max-w-none text-slate-600">
                                {!! $batch->course->description !!}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="card p-6">
                            <h3 class="font-display text-base font-bold text-slate-900">Your trainer</h3>
                            <div class="mt-4 flex items-center gap-3">
                                <div class="grid h-12 w-12 place-items-center rounded-full bg-brand-100 font-display text-lg font-bold text-brand-700">
                                    {{ strtoupper(substr($batch->trainer?->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $batch->trainer?->name ?? 'TBA' }}</p>
                                    <p class="text-xs text-slate-500">{{ $batch->trainer?->expertise }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">{{ $batch->trainer?->bio }}</p>
                        </div>

                        <div class="card p-6">
                            <h3 class="font-display text-base font-bold text-slate-900">Payment Summary</h3>
                            <dl class="mt-4 space-y-3 text-sm">
                                <div class="flex justify-between"><dt class="text-slate-500">Course fee</dt><dd class="font-semibold text-slate-800">৳{{ number_format($enrollment->course_fee) }}</dd></div>
                                <div class="flex justify-between"><dt class="text-slate-500">Discount</dt><dd class="font-semibold text-emerald-600">− ৳{{ number_format($enrollment->discount) }}</dd></div>
                                <div class="flex justify-between border-t border-slate-100 pt-3"><dt class="font-semibold text-slate-700">Final fee</dt><dd class="font-bold text-slate-900">৳{{ number_format($enrollment->final_fee) }}</dd></div>
                                <div class="flex justify-between"><dt class="text-slate-500">Paid</dt><dd class="font-semibold text-slate-800">৳{{ number_format($enrollment->paid) }}</dd></div>
                                <div class="flex justify-between"><dt class="text-slate-500">Due</dt><dd class="font-semibold {{ $enrollment->due > 0 ? 'text-red-600' : 'text-emerald-600' }}">৳{{ number_format($enrollment->due) }}</dd></div>
                            </dl>
                            @if ($enrollment->due > 0)
                                <a href="{{ route('student.payments') }}" class="btn-primary mt-5 w-full">Pay Due</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Schedule --}}
            @if ($tab === 'schedule')
                <div class="card overflow-hidden">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h3 class="font-display text-base font-bold text-slate-900">Class Schedule</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Date</th>
                                    <th class="px-6 py-3 font-semibold">Topic</th>
                                    <th class="px-6 py-3 font-semibold">Time</th>
                                    <th class="px-6 py-3 font-semibold">Room</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($schedule as $session)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-3.5 font-medium text-slate-700">{{ $session->date->format('d M Y') }} <span class="text-slate-400">({{ $session->date->format('D') }})</span></td>
                                        <td class="px-6 py-3.5 text-slate-700">{{ $session->topic ?? '—' }}</td>
                                        <td class="px-6 py-3.5 text-slate-600">{{ $session->start_time?->format('g:i A') }} – {{ $session->end_time?->format('g:i A') }}</td>
                                        <td class="px-6 py-3.5 text-slate-600">{{ $session->room ?? '—' }}</td>
                                        <td class="px-6 py-3.5">
                                            <span class="badge {{ $session->status === 'completed' ? 'badge-green' : ($session->status === 'cancelled' ? 'badge-red' : 'badge-blue') }}">{{ ucfirst($session->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">No class sessions scheduled yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Materials --}}
            @if ($tab === 'materials')
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($materials as $material)
                        <div class="card p-5">
                            <div class="flex items-start gap-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                        @if ($material->type === 'video')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        @elseif ($material->type === 'archive')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        @endif
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-semibold text-slate-800">{{ $material->title }}</h4>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ ucfirst($material->type) }}</p>
                                </div>
                            </div>
                            @if ($material->description)
                                <p class="mt-3 line-clamp-2 text-sm text-slate-500">{{ $material->description }}</p>
                            @endif
                            <div class="mt-4 border-t border-slate-100 pt-3">
                                @if ($material->file_path)
                                    <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="btn-secondary w-full text-xs">Download</a>
                                @elseif ($material->external_url)
                                    <a href="{{ $material->external_url }}" target="_blank" class="btn-outline w-full text-xs">Open Link</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3">
                            <x-student.empty-state title="No materials yet" description="Your trainer hasn't shared any materials for this batch yet." />
                        </div>
                    @endforelse
                </div>
            @endif

            {{-- Assignments --}}
            @if ($tab === 'assignments')
                <div class="space-y-4">
                    @forelse ($assignments as $assignment)
                        <div class="card flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-amber-50 text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-800">{{ $assignment->title }}</h4>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $assignment->total_marks }} marks ·
                                        @if ($assignment->deadline)
                                            Due {{ $assignment->deadline->format('d M Y, g:i A') }}
                                            <span class="{{ $assignment->is_overdue ? 'text-red-600' : 'text-slate-500' }}">({{ $assignment->deadline->diffForHumans() }})</span>
                                        @else
                                            No deadline
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if ($assignment->attachment)
                                <a href="{{ Storage::url($assignment->attachment) }}" target="_blank" class="btn-secondary shrink-0 text-xs">Download Attachment</a>
                            @endif
                        </div>
                    @empty
                        <x-student.empty-state title="No assignments" description="No assignments have been posted for this batch yet." />
                    @endforelse
                </div>
            @endif

            {{-- Attendance --}}
            @if ($tab === 'attendance')
                <div class="card overflow-hidden">
                    <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="font-display text-base font-bold text-slate-900">Attendance Record</h3>
                        <span class="badge badge-green">{{ $this->attendancePercentage }}% attendance</span>
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
                                @forelse ($attendance as $record)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-3.5 font-medium text-slate-700">{{ $record->date->format('d M Y') }}</td>
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
            @endif

            {{-- Results --}}
            @if ($tab === 'results')
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($results as $result)
                        <div class="card p-6">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-800">{{ $result->exam->title }}</h4>
                                <span class="badge {{ match ($result->grade) { 'A+', 'A', 'B' => 'badge-green', 'C', 'D' => 'badge-amber', default => 'badge-red' } }}">{{ $result->grade }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">{{ $result->exam->exam_date->format('d M Y') }}</p>
                            <div class="mt-4">
                                <div class="flex items-baseline gap-2">
                                    <p class="font-display text-2xl font-bold text-slate-900">{{ $result->marks }}<span class="text-sm font-medium text-slate-400"> / {{ $result->exam->total_marks }}</span></p>
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
    @endif
</div>