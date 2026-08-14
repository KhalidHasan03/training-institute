<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Assignments</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name : 'No active batch' }}</p>
        </div>
        @if ($this->batch)
            <span class="badge {{ $this->pendingAssignments > 0 ? 'badge-amber' : 'badge-green' }}">{{ $this->pendingAssignments }} pending</span>
        @endif
    </div>

    @if (! $this->batch)
        <div class="mt-6">
            <x-student.empty-state title="No active enrollment" description="Enroll in a course to receive assignments.">
                <a href="{{ route('public.courses') }}" class="btn-primary">Browse Courses</a>
            </x-student.empty-state>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @forelse ($assignments as $assignment)
                <div class="card p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-amber-50 text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-semibold text-slate-800">{{ $assignment->title }}</h4>
                                    @if ($assignment->is_overdue)
                                        <span class="badge badge-red">Overdue</span>
                                    @endif
                                </div>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    {{ number_format((float) $assignment->total_marks) }} marks ·
                                    @if ($assignment->deadline)
                                        Due {{ $assignment->deadline->format('d M Y, g:i A') }}
                                        <span class="{{ $assignment->is_overdue ? 'text-red-600' : 'text-slate-400' }}">({{ $assignment->deadline->diffForHumans() }})</span>
                                    @else
                                        No deadline
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            @if ($assignment->attachment)
                                <a href="{{ Storage::url($assignment->attachment) }}" target="_blank" class="btn-secondary text-xs">Download Attachment</a>
                            @endif
                        </div>
                    </div>
                    @if ($assignment->description)
                        <p class="mt-4 border-t border-slate-100 pt-4 text-sm leading-relaxed text-slate-500">{{ $assignment->description }}</p>
                    @endif
                </div>
            @empty
                <x-student.empty-state title="No assignments" description="No assignments have been posted for this batch yet." />
            @endforelse
        </div>
    @endif
</div>
