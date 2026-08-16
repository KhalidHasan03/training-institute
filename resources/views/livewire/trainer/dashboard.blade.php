<div>
    @if ($trainer = $this->trainer)
        {{-- Greeting --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-2xl font-bold text-slate-900">
                    {{ now()->format('G') < 12 ? 'Good Morning' : (now()->format('G') < 17 ? 'Good Afternoon' : 'Good Evening') }}, {{ $trainer->name }} 👋
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $trainer->expertise ?: 'Trainer' }} · Managing {{ $this->activeBatchCount }} active {{ \Illuminate\Support\Str::plural('batch', $this->activeBatchCount) }}
                </p>
            </div>
            @if ($this->batch)
                <div class="flex items-center gap-2">
                    <span class="badge bg-slate-900 text-white">
                        {{ $this->batch->course->title }} — Batch {{ $this->batch->name }}
                    </span>
                </div>
            @endif
        </div>

        @if ($this->batchCount === 0)
            <div class="mt-6">
                <x-trainer.empty-state
                    title="No batches assigned yet"
                    description="You don't have any batches assigned. Please contact the admin to assign batches to your trainer profile."
                />
            </div>
        @else
            {{-- Stats --}}
            <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Active Batches</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-brand-600"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->activeBatchCount }}<span class="text-sm font-medium text-slate-400"> / {{ $this->batchCount }}</span></p>
                    <p class="mt-1 text-xs text-slate-400">Assigned batches</p>
                </div>
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Students</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->totalStudents }}</p>
                    <p class="mt-1 text-xs text-slate-400">Enrolled across batches</p>
                </div>
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Attendance</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-amber-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->attendanceRate }}%</p>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-amber-500 transition-all" style="width: {{ $this->attendanceRate }}%"></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Exams</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625Z" /></svg>
                    </div>
                    <p class="mt-2 font-display text-2xl font-bold text-slate-900">{{ $this->examCount }}</p>
                    <a href="{{ route('trainer.exams') }}" class="mt-1 text-xs font-semibold text-brand-600 hover:text-brand-700">Manage exams →</a>
                </div>
            </div>

            {{-- Main grid --}}
            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    {{-- Today's classes --}}
                    <div class="card overflow-hidden">
                        <div class="border-b border-slate-100 bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-5 text-white">
                            <p class="text-xs font-medium uppercase tracking-wider text-brand-200">Today's Classes</p>
                            <h3 class="mt-1 font-display text-lg font-bold">{{ $this->todaySessions->count() ? 'You have '.$this->todaySessions->count().' session'.($this->todaySessions->count() > 1 ? 's' : '').' today' : 'No classes scheduled today' }}</h3>
                        </div>
                        @if ($this->todaySessions->count())
                            <div class="divide-y divide-slate-100">
                                @foreach ($this->todaySessions as $session)
                                    <div class="flex items-center gap-4 px-6 py-4">
                                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold text-slate-800">{{ $session->topic ?? $session->batch->course->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $session->batch->name }} · {{ $session->start_time?->format('g:i A') }} – {{ $session->end_time?->format('g:i A') }} · {{ $session->room ?? 'TBA' }}</p>
                                        </div>
                                        <a href="{{ route('trainer.sessions') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Manage</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Upcoming sessions --}}
                    <div class="card p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="font-display text-base font-bold text-slate-900">Upcoming Sessions</h3>
                            <a href="{{ route('trainer.sessions') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">View all →</a>
                        </div>
                        <div class="mt-4 divide-y divide-slate-100">
                            @forelse ($this->upcomingAllSessions as $session)
                                <div class="flex items-center gap-4 py-3">
                                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-50 text-center">
                                        <div>
                                            <p class="text-sm font-bold text-brand-700">{{ $session->date->format('d') }}</p>
                                            <p class="text-[10px] font-semibold uppercase text-slate-400">{{ $session->date->format('M') }}</p>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-800">{{ $session->topic ?? $session->batch->course->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $session->batch->name }} · {{ $session->start_time?->format('g:i A') }} – {{ $session->end_time?->format('g:i A') }} · {{ $session->room ?? 'TBA' }}</p>
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $session->date->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="py-6 text-center text-sm text-slate-400">No upcoming sessions.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recent enrollments --}}
                    <div class="card overflow-hidden">
                        <div class="border-b border-slate-100 px-6 py-4">
                            <h3 class="font-display text-base font-bold text-slate-900">Recent Enrollments</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">Student</th>
                                        <th class="px-6 py-3 font-semibold">Batch</th>
                                        <th class="px-6 py-3 font-semibold">Enrolled</th>
                                        <th class="px-6 py-3 font-semibold">Payment</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($recentEnrollments as $enrollment)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-3.5">
                                                <p class="font-medium text-slate-800">{{ $enrollment->student->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $enrollment->student->student_id }}</p>
                                            </td>
                                            <td class="px-6 py-3.5 text-slate-600">{{ $enrollment->batch->name }}</td>
                                            <td class="px-6 py-3.5 text-slate-600">{{ $enrollment->enrollment_date->format('d M Y') }}</td>
                                            <td class="px-6 py-3.5">
                                                <span class="badge {{ in_array($enrollment->payment_status, ['paid', 'free']) ? 'badge-green' : ($enrollment->payment_status === 'partial' ? 'badge-amber' : 'badge-red') }}">
                                                    {{ ucfirst($enrollment->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400">No enrollments yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Selected batch details --}}
                    @if ($this->batch)
                        <div class="card p-6">
                            <h3 class="font-display text-base font-bold text-slate-900">Current Batch</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-500">Course</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $this->batch->course->title }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-500">Batch</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $this->batch->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-500">Students</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $this->batch->enrolled_count }} / {{ $this->batch->max_students }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-500">Period</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $this->batch->start_date->format('d M') }} – {{ $this->batch->end_date->format('d M Y') }}</span>
                                </div>
                                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                                    @php $fill = $this->batch->max_students > 0 ? min(100, (int) round($this->batch->enrolled_count / $this->batch->max_students * 100)) : 0; @endphp
                                    <div class="h-full rounded-full {{ $fill >= 100 ? 'bg-red-500' : 'bg-brand-500' }} transition-all" style="width: {{ $fill }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endif

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
            </div>
        @endif
    @else
        <x-trainer.empty-state
            title="No trainer profile linked"
            description="Your account is not linked to a trainer profile. Please contact the institute office for assistance."
        />
    @endif
</div>