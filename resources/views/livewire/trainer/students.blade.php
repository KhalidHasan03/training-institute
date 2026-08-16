<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Students</h1>
            <p class="mt-1 text-sm text-slate-500">Students enrolled across your batches</p>
        </div>
        <span class="badge badge-blue">{{ $this->filteredEnrollments->unique('student_id')->count() }} students</span>
    </div>

    {{-- Filters --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap gap-2">
            <select wire:model="filterBatch" class="input !w-auto">
                <option value="all">All batches</option>
                @foreach ($this->batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->name }} — {{ $batch->course->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by name, ID or email..."
                   class="input !w-72 !pl-9">
        </div>
    </div>

    @if ($this->batchCount === 0)
        <div class="mt-6">
            <x-trainer.empty-state title="No batches assigned" description="Once the admin assigns batches to you, the enrolled students will appear here." />
        </div>
    @elseif ($enrollments->isEmpty())
        <div class="mt-6">
            <x-trainer.empty-state title="No students found" description="Try changing the batch filter or search term." />
        </div>
    @else
        <div class="card mt-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Student</th>
                            <th class="px-6 py-3 font-semibold">Batch</th>
                            <th class="px-6 py-3 font-semibold">Contact</th>
                            <th class="px-6 py-3 font-semibold">Enrolled</th>
                            <th class="px-6 py-3 font-semibold">Payment</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($enrollments as $enrollment)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-accent-600 text-xs font-bold text-white">
                                            {{ strtoupper(substr($enrollment->student->name ?? '?', 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $enrollment->student->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $enrollment->student->student_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $enrollment->batch->name }}</td>
                                <td class="px-6 py-3.5">
                                    <p class="text-slate-600">{{ $enrollment->student->phone ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $enrollment->student->email ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $enrollment->enrollment_date->format('d M Y') }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="badge {{ $enrollment->payment_status === 'paid' ? 'badge-green' : ($enrollment->payment_status === 'partial' ? 'badge-amber' : 'badge-red') }}">
                                        {{ ucfirst($enrollment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="badge {{ $enrollment->status === 'active' ? 'badge-green' : 'badge-slate' }}">{{ ucfirst($enrollment->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>