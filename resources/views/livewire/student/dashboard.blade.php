<div>
    @if ($student = $this->student)
        {{-- Greeting --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-2xl font-bold text-slate-900">
                    {{ now()->format('G') < 12 ? 'Good Morning' : (now()->format('G') < 17 ? 'Good Afternoon' : 'Good Evening') }}, {{ $student->name }} 👋
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @if ($this->enrollment)
                        {{ $this->batch->course->title }} · Batch {{ $this->batch->name }}
                    @else
                        Your learning journey starts here.
                    @endif
                </p>
            </div>
            <div>
                <span class="badge bg-slate-900 text-white">
                    Student ID: {{ $student->student_id }}
                </span>
            </div>
        </div>

        @if (! $this->enrollment)
            <x-student.empty-state
                title="You are not enrolled in any course"
                description="Browse our courses and enroll in a batch to start learning. Your dashboard will show progress once you enroll."
            >
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        @else
            {{-- Stats --}}
            <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Course Progress</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-brand-600"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->progress }}%</p>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-600 transition-all" style="width: {{ $this->progress }}%"></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Attendance</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->attendancePercentage }}%</p>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $this->attendancePercentage }}%"></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Pending Assignments</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-amber-600"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->pendingAssignments }}</p>
                    <p class="mt-2 text-xs text-slate-400">Need your attention</p>
                </div>
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Payment Due</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 {{ $this->pendingDue > 0 ? 'text-red-600' : 'text-emerald-600' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold {{ $this->pendingDue > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        ৳{{ number_format($this->pendingDue) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-400">{{ $this->pendingDue > 0 ? 'Due on enrollment' : 'All paid' }}</p>
                </div>
            </div>

            {{-- Next class + announcements --}}
            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    {{-- Next class --}}
                    <div class="card overflow-hidden">
                        <div class="border-b border-slate-100 bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-5 text-white">
                            @php $next = $this->nextClass; @endphp
                            @if ($next)
                                <p class="text-xs font-medium uppercase tracking-wider text-brand-200">Next Class</p>
                                <h3 class="mt-1 font-display text-lg font-bold">{{ $next->topic ?? $this->batch->course->title }}</h3>
                            @else
                                <p class="text-xs font-medium uppercase tracking-wider text-brand-200">Next Class</p>
                                <h3 class="mt-1 font-display text-lg font-bold">No upcoming class</h3>
                            @endif
                        </div>
                        @if ($next)
                            <div class="grid grid-cols-3 divide-x divide-slate-100">
                                <div class="px-4 py-4 text-center">
                                    <p class="text-xs text-slate-400">Date</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $next->date->format('d M Y') }}</p>
                                </div>
                                <div class="px-4 py-4 text-center">
                                    <p class="text-xs text-slate-400">Time</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $next->start_time?->format('g:i A') }} – {{ $next->end_time?->format('g:i A') }}</p>
                                </div>
                                <div class="px-4 py-4 text-center">
                                    <p class="text-xs text-slate-400">Room</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">{{ $next->room ?? '—' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Recently completed / today --}}
                    <div class="card p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="font-display text-base font-bold text-slate-900">Upcoming classes</h3>
                            <a href="{{ route('student.schedule') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">View schedule →</a>
                        </div>
                        <div class="mt-4 divide-y divide-slate-100">
                            @forelse ($this->upcomingSessions as $session)
                                <div class="flex items-center gap-4 py-3">
                                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-50 text-center">
                                        <div>
                                            <p class="text-sm font-bold text-brand-700">{{ $session->date->format('d') }}</p>
                                            <p class="text-[10px] font-semibold uppercase text-slate-400">{{ $session->date->format('M') }}</p>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-800">{{ $session->topic ?? $this->batch->course->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $session->start_time?->format('g:i A') }} – {{ $session->end_time?->format('g:i A') }} · {{ $session->room ?? 'TBA' }}</p>
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $session->date->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="py-6 text-center text-sm text-slate-400">No upcoming classes.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Announcements --}}
                <div class="card p-6">
                    <h3 class="font-display text-base font-bold text-slate-900">Announcements</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($this->announcements as $announcement)
                            <div class="rounded-xl border border-slate-100 p-4">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-semibold text-slate-800">{{ $announcement->title }}</h4>
                                    <span class="text-xs font-medium text-slate-400">{{ $announcement->published_at?->format('d M') ?? $announcement->created_at->format('d M') }}</span>
                                </div>
                                <p class="mt-1.5 text-sm text-slate-500 line-clamp-3">{!! \Illuminate\Support\Str::limit(strip_tags($announcement->content), 110) !!}</p>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-slate-400">No announcements yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    @else
        <x-student.empty-state
            title="No student profile linked"
            description="Your account is not linked to a student profile. Please contact the institute office for assistance."
        />
    @endif
</div>