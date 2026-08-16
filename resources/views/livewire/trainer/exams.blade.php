<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Exams</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $this->batch?->name ? 'Batch ' . $this->batch->name . ' · ' . $this->batch->course->title : 'No batch selected' }}</p>
        </div>
        @if ($this->batch)
            <button wire:click="openCreate" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New Exam
            </button>
        @endif
    </div>

    @if (session()->has('trainer-exam-saved'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">{{ session('trainer-exam-saved') }}</div>
    @endif

    @if (session()->has('trainer-exam-error'))
        <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">{{ session('trainer-exam-error') }}</div>
    @endif

    @if (! $this->batch)
        <div class="mt-6">
            <x-trainer.empty-state title="No batch selected" description="Use the batch switcher in the top bar to pick a batch and manage its exams." />
        </div>
    @else
        {{-- Create / Edit form --}}
        @if ($showCreate || $showEdit)
            <div class="card mt-6 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-base font-bold text-slate-900">{{ $showEdit ? 'Edit Exam' : 'New Exam' }} — {{ $this->batch->name }}</h3>
                    <button wire:click="closeForm" class="text-sm font-semibold text-slate-400 hover:text-slate-600">Cancel ✕</button>
                </div>

                <form wire:submit="save" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="label" for="formTitle">Exam title</label>
                        <input class="input" id="formTitle" wire:model="formTitle" placeholder="e.g. Mid Term Exam">
                        @error('formTitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="formExamDate">Exam date</label>
                        <input class="input" id="formExamDate" type="date" wire:model="formExamDate">
                        @error('formExamDate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="formTotalMarks">Total marks</label>
                        <input class="input" id="formTotalMarks" type="number" step="0.01" wire:model="formTotalMarks">
                        @error('formTotalMarks') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3">
                        <label class="label" for="formDescription">Description</label>
                        <textarea class="input" id="formDescription" rows="2" wire:model="formDescription" placeholder="Optional notes about this exam"></textarea>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3 border-t border-slate-100 pt-4">
                        <button type="submit" class="btn-primary">{{ $showEdit ? 'Update Exam' : 'Create Exam' }}</button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Grading form --}}
        @if ($this->gradingId)
            @php $gradingExam = $this->exams->firstWhere('id', $this->gradingId); @endphp
            @if ($gradingExam)
                <div class="card mt-6 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-slate-100 bg-brand-50/50 px-6 py-4">
                        <div>
                            <h3 class="font-display text-base font-bold text-slate-900">Enter Results — {{ $gradingExam->title }}</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Full marks: {{ $gradingExam->total_marks }}</p>
                        </div>
                        <button wire:click="closeGrading" class="text-sm font-semibold text-slate-400 hover:text-slate-600">Close ✕</button>
                    </div>
                    <form wire:submit="saveResults" class="p-6">
                        <div class="overflow-hidden rounded-2xl border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Student</th>
                                        <th class="px-4 py-3 font-semibold">Marks</th>
                                        <th class="px-4 py-3 font-semibold">Grade</th>
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
                                                <input type="number" step="0.01" min="0" max="{{ $gradingExam->total_marks }}" placeholder="—"
                                                       wire:model.debounce.300ms="marks.{{ $student->id }}"
                                                       class="input !w-28">
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $m = $marks[(string) $student->id] ?? null;
                                                    $grade = ($m === '' || $m === null) ? null : \App\Services\ResultService::gradeForPercentage((float) $m / (float) $gradingExam->total_marks * 100);
                                                @endphp
                                                @if ($grade)
                                                    <span class="badge {{ in_array($grade, ['A+', 'A', 'B']) ? 'badge-green' : (in_array($grade, ['C', 'D']) ? 'badge-amber' : 'badge-red') }}">{{ $grade }}</span>
                                                @else
                                                    <span class="text-xs text-slate-400">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 py-10 text-center text-slate-400">No students enrolled in this batch yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-primary">Save Results</button>
                        </div>
                    </form>
                </div>
            @endif
        @endif

        {{-- Exams list --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($exams as $exam)
                <div class="card overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-display text-base font-bold text-slate-900">{{ $exam->title }}</h3>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $exam->exam_date->format('d M Y') }}</p>
                            </div>
                            <span class="badge badge-blue">{{ $exam->results_count }} / {{ $this->batch->enrolled_count }} results</span>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <p class="font-display text-2xl font-bold text-slate-900">{{ number_format((float) $exam->total_marks) }}</p>
                            <span class="text-sm text-slate-400">marks</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                            @php $fill = $this->batch->enrolled_count > 0 ? min(100, (int) round($exam->results_count / $this->batch->enrolled_count * 100)) : 0; @endphp
                            <div class="h-full rounded-full bg-brand-500 transition-all" style="width: {{ $fill }}%"></div>
                        </div>
                        @if ($exam->description)
                            <p class="mt-3 text-xs text-slate-500 line-clamp-2">{{ $exam->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 border-t border-slate-100 bg-slate-50/50 px-5 py-3">
                        <button wire:click="openGrading({{ $exam->id }})" class="btn-primary !px-3 !py-1.5 !text-xs">Enter Results</button>
                        <button wire:click="openEdit({{ $exam->id }})" class="text-sm font-semibold text-slate-600 hover:text-slate-800">Edit</button>
                        <button wire:click="delete({{ $exam->id }})" wire:confirm="Delete {{ $exam->title }}? All results will be removed."
                                class="text-sm font-semibold text-red-600 hover:text-red-700">Delete</button>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3">
                    <x-trainer.empty-state title="No exams yet" description="Create an exam for this batch to get started.">
                        <button wire:click="openCreate" class="btn-primary">Create your first exam</button>
                    </x-trainer.empty-state>
                </div>
            @endforelse
        </div>
    @endif
</div>