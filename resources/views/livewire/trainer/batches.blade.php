<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Batches</h1>
            <p class="mt-1 text-sm text-slate-500">Manage the batches assigned to you</p>
        </div>
        <div class="flex items-center gap-2">
            @if (session()->has('trainer-batch-saved'))
                <span class="badge badge-green">{{ session('trainer-batch-saved') }}</span>
            @endif
            <button wire:click="openCreate" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Batch
            </button>
        </div>
    </div>

    {{-- Create / Edit form --}}
    @if ($showCreate || $showEdit)
        <div class="card mt-6 p-6">
            <div class="flex items-center justify-between">
                <h3 class="font-display text-base font-bold text-slate-900">{{ $showEdit ? 'Edit Batch' : 'Create Batch' }}</h3>
                <button wire:click="closeForm" class="text-sm font-semibold text-slate-400 hover:text-slate-600">Cancel ✕</button>
            </div>

            <form wire:submit="save" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="label" for="formName">Batch name</label>
                    <input class="input" id="formName" wire:model="formName" placeholder="e.g. LD-16">
                    @error('formName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formCourseId">Course</label>
                    <select class="input" id="formCourseId" wire:model="formCourseId">
                        <option value="">Select course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    @error('formCourseId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formMaxStudents">Max students</label>
                    <input class="input" id="formMaxStudents" type="number" wire:model="formMaxStudents">
                    @error('formMaxStudents') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formStartDate">Start date</label>
                    <input class="input" id="formStartDate" type="date" wire:model="formStartDate">
                    @error('formStartDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formEndDate">End date</label>
                    <input class="input" id="formEndDate" type="date" wire:model="formEndDate">
                    @error('formEndDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formStatus">Status</label>
                    <select class="input" id="formStatus" wire:model="formStatus">
                        <option value="upcoming">Upcoming</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="formStartTime">Start time</label>
                    <input class="input" id="formStartTime" type="time" wire:model="formStartTime">
                    @error('formStartTime') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formEndTime">End time</label>
                    <input class="input" id="formEndTime" type="time" wire:model="formEndTime">
                    @error('formEndTime') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="formRoom">Room</label>
                    <input class="input" id="formRoom" wire:model="formRoom" placeholder="e.g. Room 201">
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="label">Class days</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $day)
                            <label class="flex cursor-pointer items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-1.5 text-sm font-medium {{ in_array($day, $formClassDays ?? []) ? 'border-brand-500 bg-brand-50 text-brand-700' : 'text-slate-600' }}">
                                <input type="checkbox" value="{{ $day }}" wire:model="formClassDays" class="hidden">
                                {{ $day === 'Thu' ? 'Th' : $day }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="sm:col-span-2 lg:col-span-3 border-t border-slate-100 pt-4">
                    <button type="submit" class="btn-primary">{{ $showEdit ? 'Update Batch' : 'Create Batch' }}</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Filters --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap gap-1 rounded-xl border border-slate-200 bg-white p-1">
            @foreach (['all' => 'All', 'active' => 'Active', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $key => $label)
                <button wire:click="$set('status', '{{ $key }}')"
                        class="rounded-lg px-4 py-1.5 text-sm font-medium transition-colors {{ $status === $key ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }} ({{ $key === 'all' ? $this->batches->count() : $this->batches->where('status', $key)->count() }})
                </button>
            @endforeach
        </div>
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search batches..."
                   class="input !w-64 !pl-9">
        </div>
    </div>

    {{-- List --}}
    @if ($this->batchCount === 0)
        <div class="mt-6">
            <x-trainer.empty-state title="No batches assigned" description="When the admin assigns batches to you they will appear here. You can also create your own batch below.">
                <button wire:click="openCreate" class="btn-primary">Create your first batch</button>
            </x-trainer.empty-state>
        </div>
    @elseif ($batches->isEmpty())
        <div class="mt-6">
            <x-trainer.empty-state title="No matching batches" description="Try changing the status filter or search term." />
        </div>
    @else
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($batches as $batch)
                <div class="card overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-display text-base font-bold text-slate-900">{{ $batch->name }}</h3>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $batch->course->title }}</p>
                            </div>
                            <span class="badge {{ $batch->status === 'active' ? 'badge-green' : ($batch->status === 'upcoming' ? 'badge-blue' : ($batch->status === 'completed' ? 'badge-slate' : 'badge-red')) }}">{{ ucfirst($batch->status) }}</span>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div>
                                <p class="text-xs text-slate-400">Students</p>
                                <p class="mt-0.5 text-sm font-semibold text-slate-800">{{ $batch->enrolled_count }}/{{ $batch->max_students }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Schedule</p>
                                <p class="mt-0.5 text-sm font-semibold text-slate-800">{{ $batch->class_days }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Time</p>
                                <p class="mt-0.5 text-sm font-semibold text-slate-800">{{ $batch->start_time?->format('g:i A') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            @php $fill = $batch->max_students > 0 ? min(100, (int) round($batch->enrolled_count / $batch->max_students * 100)) : 0; @endphp
                            <div class="h-full rounded-full {{ $fill >= 100 ? 'bg-red-500' : 'bg-brand-500' }} transition-all" style="width: {{ $fill }}%"></div>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ $batch->start_date->format('d M') }} – {{ $batch->end_date->format('d M Y') }}</span>
                            <span>{{ $batch->room ?? 'TBA' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-5 py-3">
                        <button wire:click="openEdit({{ $batch->id }})" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Edit</button>
                        <button wire:click="delete({{ $batch->id }})" wire:confirm="Delete batch {{ $batch->name }}? This cannot be undone."
                                class="text-sm font-semibold text-red-600 hover:text-red-700">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>