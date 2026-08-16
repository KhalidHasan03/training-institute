<?php

namespace App\Livewire\Trainer;

use App\Models\Batch;
use App\Models\Course;
use Illuminate\Support\Collection;

class Batches extends TrainerComponent
{
    public string $status = 'all';

    public string $search = '';

    public bool $showCreate = false;

    public bool $showEdit = false;

    public ?int $editingId = null;

    public ?Batch $editing = null;

    public string $formName = '';

    public ?int $formCourseId = null;

    public $formStartDate;

    public $formEndDate;

    public $formClassDays = [];

    public $formStartTime;

    public $formEndTime;

    public ?string $formRoom = null;

    public ?int $formMaxStudents = 30;

    public string $formStatus = 'active';

    public bool $saved = false;

    public function openCreate()
    {
        $this->resetBatchForm();

        $this->showEdit = false;
        $this->showCreate = true;
    }

    public function openEdit(int $batchId)
    {
        $batch = Batch::where('trainer_id', $this->trainer->id)->findOrFail($batchId);

        $this->editing = $batch;
        $this->editingId = $batch->id;
        $this->formName = $batch->name;
        $this->formCourseId = $batch->course_id;
        $this->formStartDate = $batch->start_date?->toDateString();
        $this->formEndDate = $batch->end_date?->toDateString();
        $this->formClassDays = array_values(array_filter(array_map('trim', explode(',', (string) $batch->class_days))));
        $this->formStartTime = $batch->start_time?->format('H:i');
        $this->formEndTime = $batch->end_time?->format('H:i');
        $this->formRoom = $batch->room;
        $this->formMaxStudents = $batch->max_students;
        $this->formStatus = $batch->status;

        $this->showCreate = false;
        $this->showEdit = true;
    }

    public function closeForm(): void
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->editing = null;
        $this->editingId = null;
        $this->resetBatchForm();
    }

    public function save()
    {
        $validated = $this->validate([
            'formName' => ['required', 'string', 'max:255'],
            'formCourseId' => ['required', 'integer', 'exists:courses,id'],
            'formStartDate' => ['required', 'date'],
            'formEndDate' => ['required', 'date', 'after_or_equal:formStartDate'],
            'formClassDays' => ['nullable', 'array'],
            'formClassDays.*' => ['string', 'max:3'],
            'formStartTime' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'formEndTime' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'formRoom' => ['nullable', 'string', 'max:255'],
            'formMaxStudents' => ['required', 'integer', 'min:1', 'max:1000'],
            'formStatus' => ['required', 'in:active,upcoming,completed,inactive'],
        ]);

        $data = [
            'course_id' => $validated['formCourseId'],
            'trainer_id' => $this->trainer->id,
            'name' => $validated['formName'],
            'start_date' => $validated['formStartDate'],
            'end_date' => $validated['formEndDate'],
            'class_days' => implode(', ', $validated['formClassDays'] ?? []),
            'start_time' => $validated['formStartTime'],
            'end_time' => $validated['formEndTime'],
            'room' => $validated['formRoom'],
            'max_students' => $validated['formMaxStudents'],
            'status' => $validated['formStatus'],
        ];

        if ($this->editingId) {
            $batch = Batch::where('trainer_id', $this->trainer->id)->findOrFail($this->editingId);
            $batch->update($data);
        } else {
            Batch::create($data);
        }

        $this->saved = true;
        session()->flash('trainer-batch-saved', $this->editingId ? 'Batch updated successfully.' : 'Batch created successfully.');
        $this->closeForm();
    }

    public function delete(int $batchId): void
    {
        Batch::where('trainer_id', $this->trainer->id)->findOrFail($batchId)->delete();

        session()->flash('trainer-batch-saved', 'Batch deleted successfully.');
    }

    public function getCoursesProperty(): Collection
    {
        return Course::where('status', 'active')->orderBy('title')->get();
    }

    public function getFilteredBatchesProperty(): Collection
    {
        return $this->batches
            ->when($this->status !== 'all', fn (Collection $items) => $items->where('status', $this->status))
            ->when($this->search !== '', fn (Collection $items) => $items->filter(fn (Batch $b) => str_contains(strtolower($b->name.' '.($b->course->title ?? '')), strtolower($this->search))));
    }

    public function resetBatchForm(): void
    {
        $this->formName = '';
        $this->formCourseId = null;
        $this->formStartDate = null;
        $this->formEndDate = null;
        $this->formClassDays = [];
        $this->formStartTime = null;
        $this->formEndTime = null;
        $this->formRoom = null;
        $this->formMaxStudents = 30;
        $this->formStatus = 'active';
    }

    public function render()
    {
        return view('livewire.trainer.batches', [
            'batches' => $this->filteredBatches,
            'courses' => $this->courses,
        ]);
    }
}
