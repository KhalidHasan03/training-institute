<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Class Sessions</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name . ' · ' . $this->batch->course->title : 'No batch selected' }}</p>
        </div>
        @if ($this->batch)
            <button wire:click="openCreate" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Session
            </button>
        @endif
    </div>

    @if (session()->has('trainer-session-saved'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">{{ session('trainer-session-saved') }}</div>
    @endif

    @if (session()->has('trainer-session-error'))
        <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">{{ session('trainer-session-error') }}</div>
    @endif

    @if (! $this->batch)
        <div class="mt-6">
            <x-trainer.empty-state title="No batch selected" description="Use the batch switcher in the top bar to pick a batch and manage its class sessions." />
        </div>
    @else
        {{-- Create / Edit form --}}
        @if ($showCreate || $showEdit)
            <div class="card mt-6 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-bold text-slate-900">{{ $showEdit ? 'Edit Session' : 'New Session' }} — {{ $this->batch->name }}</h3>
                    <button wire:click="closeForm" class="text-sm font-semibold text-slate-400 hover:text-slate-600">Cancel ✕</button>
                </div>

                <form wire:submit="save" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="label" for="formDate">Date</label>
                        <input class="input" id="formDate" type="date" wire:model="formDate">
                        @error('formDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                        <label class="label" for="formTopic">Topic</label>
                        <input class="input" id="formTopic" wire:model="formTopic" placeholder="e.g. Lesson 7: Controllers">
                    </div>
                    <div>
                        <label class="label" for="formRoom">Room</label>
                        <input class="input" id="formRoom" wire:model="formRoom" placeholder="e.g. Room 201">
                    </div>
                    <div>
                        <label class="label" for="formStatus">Status</label>
                        <select class="input" id="formStatus" wire:model="formStatus">
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3">
                        <label class="label" for="formNotes">Notes</label>
                        <textarea class="input" id="formNotes" rows="3" wire:model="formNotes" placeholder="Optional notes about this session"></textarea>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3 border-t border-slate-100 pt-4">
                        <button type="submit" class="btn-primary">{{ $showEdit ? 'Update Session' : 'Create Session' }}</button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Tabs + status filter --}}
        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex gap-1 rounded-xl border border-slate-200 bg-white p-1">
                <button wire:click="$set('tab', 'upcoming')"
                        class="rounded-lg px-4 py-1.5 text-sm font-medium transition-colors {{ $tab === 'upcoming' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">Upcoming</button>
                <button wire:click="$set('tab', 'past')"
                        class="rounded-lg px-4 py-1.5 text-sm font-medium transition-colors {{ $tab === 'past' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-50' }}">Past</button>
            </div>
            <div class="flex flex-wrap gap-1 rounded-xl border border-slate-200 bg-white p-1">
                @foreach (['all' => 'All', 'scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                    <button wire:click="$set('statusFilter', '{{ $key }}')"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $statusFilter === $key ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- Sessions list --}}
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
                    <div class="hidden items-center gap-2 sm:flex">
                        <button wire:click="toggleStatus({{ $session->id }}, 'completed')"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-50" title="Mark completed">✓</button>
                        <button wire:click="toggleStatus({{ $session->id }}, 'cancelled')"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition-colors hover:bg-red-50" title="Cancel session">✕</button>
                        <button wire:click="openEdit({{ $session->id }})"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-50">Edit</button>
                        <button wire:click="delete({{ $session->id }})" wire:confirm="Delete this session?"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition-colors hover:bg-red-50">Delete</button>
                    </div>
                    <span class="badge {{ $session->status === 'completed' ? 'badge-green' : ($session->status === 'cancelled' ? 'badge-red' : 'badge-blue') }}">{{ ucfirst($session->status) }}</span>
                </div>
            @empty
                <x-trainer.empty-state
                    :title="$tab === 'upcoming' ? 'No upcoming sessions' : 'No past sessions'"
                    :description="'No sessions for batch ' . $this->batch->name . ' match these filters.'">
                    @if ($tab === 'upcoming')
                        <button wire:click="openCreate" class="btn-primary">Add a session</button>
                    @endif
                </x-trainer.empty-state>
            @endforelse
        </div>
    @endif
</div>