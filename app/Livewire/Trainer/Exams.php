<?php

namespace App\Livewire\Trainer;

use App\Models\Exam;
use App\Models\Result;
use App\Services\ResultService;
use Illuminate\Support\Collection;

class Exams extends TrainerComponent
{
    public bool $showCreate = false;

    public bool $showEdit = false;

    public ?int $editingId = null;

    public ?int $gradingId = null;

    public string $formTitle = '';

    public $formExamDate;

    public $formTotalMarks = 100;

    public ?string $formDescription = null;

    public array $marks = [];

    public function openCreate()
    {
        $this->resetFlowForm();
        $this->showEdit = false;
        $this->showCreate = true;
        $this->formExamDate = now()->addDays(7)->toDateString();
    }

    public function openEdit(int $examId)
    {
        $exam = $this->findExam($examId);

        $this->editingId = $exam->id;
        $this->formTitle = $exam->title;
        $this->formExamDate = $exam->exam_date->toDateString();
        $this->formTotalMarks = (float) $exam->total_marks;
        $this->formDescription = $exam->description;

        $this->showCreate = false;
        $this->showEdit = true;
    }

    public function closeForm(): void
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->editingId = null;
        $this->resetFlowForm();
    }

    public function save()
    {
        $batch = $this->batch;

        if (! $batch) {
            session()->flash('trainer-exam-error', 'Select a batch first to manage its exams.');

            return;
        }

        $validated = $this->validate([
            'formTitle' => ['required', 'string', 'max:255'],
            'formExamDate' => ['required', 'date'],
            'formTotalMarks' => ['required', 'numeric', 'min:1'],
            'formDescription' => ['nullable', 'string'],
        ]);

        $data = [
            'batch_id' => $batch->id,
            'title' => $validated['formTitle'],
            'exam_date' => $validated['formExamDate'],
            'total_marks' => $validated['formTotalMarks'],
            'description' => $validated['formDescription'],
        ];

        if ($this->editingId) {
            $this->findExam($this->editingId)->update($data);
        } else {
            Exam::create($data);
        }

        session()->flash('trainer-exam-saved', $this->editingId ? 'Exam updated successfully.' : 'Exam created successfully.');
        $this->closeForm();
    }

    public function delete(int $examId): void
    {
        $this->findExam($examId)->delete();

        session()->flash('trainer-exam-saved', 'Exam deleted successfully.');
    }

    public function openGrading(int $examId): void
    {
        $exam = $this->findExam($examId);

        $this->gradingId = $exam->id;
        $this->marks = [];

        $existing = Result::where('exam_id', $exam->id)->get()->keyBy('student_id');

        foreach ($this->enrolledStudents as $student) {
            $this->marks[(string) $student->id] = $existing->get($student->id)?->marks;
        }
    }

    public function closeGrading(): void
    {
        $this->gradingId = null;
        $this->marks = [];
    }

    public function saveResults()
    {
        $exam = Exam::whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
            ->findOrFail($this->gradingId);

        $this->validate([
            'marks' => ['required', 'array'],
            'marks.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $studentIds = $this->enrolledStudents->pluck('id')->map(fn ($id) => (string) $id);

        foreach ($this->marks as $studentId => $marks) {
            if (! $studentIds->contains($studentId) || $marks === '' || $marks === null) {
                continue;
            }

            $marks = min((float) $marks, (float) $exam->total_marks);

            $result = Result::updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => (int) $studentId],
                ['marks' => $marks]
            );

            $result->grade = ResultService::gradeForPercentage(
                ((float) $marks / ((float) $exam->total_marks ?: 1)) * 100
            );
            $result->save();
        }

        session()->flash('trainer-exam-saved', 'Results saved successfully.');
        $this->closeGrading();
    }

    public function findExam(int $examId): Exam
    {
        return Exam::with('results.student')
            ->whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
            ->findOrFail($examId);
    }

    public function getExamsProperty(): Collection
    {
        $batch = $this->batch;

        if (! $batch) {
            return collect();
        }

        return $batch->exams()
            ->withCount('results')
            ->orderByDesc('exam_date')
            ->get();
    }

    public function resetFlowForm(): void
    {
        $this->formTitle = '';
        $this->formExamDate = null;
        $this->formTotalMarks = 100;
        $this->formDescription = null;
    }

    public function render()
    {
        return view('livewire.trainer.exams', [
            'exams' => $this->exams,
        ]);
    }
}
