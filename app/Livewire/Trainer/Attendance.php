<?php

namespace App\Livewire\Trainer;

use App\Models\Attendance as AttendanceModel;
use App\Models\ClassSession;
use Illuminate\Support\Collection;

class Attendance extends TrainerComponent
{
    public ?int $sessionId = null;

    public array $statuses = [];

    public string $filterDate = 'all';

    public function updatedSessionId(): void
    {
        $this->loadStatuses();
    }

    public function updatedBatchId(): void
    {
        $this->sessionId = null;
        $this->statuses = [];
    }

    public function selectSession(int $sessionId): void
    {
        $this->sessionId = $sessionId;
        $this->loadStatuses();
    }

    public function loadStatuses(): void
    {
        $this->statuses = [];

        $session = $this->session;

        if (! $session) {
            return;
        }

        $existing = AttendanceModel::where('class_session_id', $session->id)->get()->keyBy('student_id');

        foreach ($this->enrolledStudents as $student) {
            $this->statuses[(string) $student->id] = $existing->get($student->id)?->status ?? 'present';
        }
    }

    public function getSessionProperty(): ?ClassSession
    {
        if (! $this->sessionId) {
            return null;
        }

        return ClassSession::with('batch.course')
            ->whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
            ->find($this->sessionId);
    }

    public function getSessionsProperty(): Collection
    {
        $batch = $this->batch;

        if (! $batch) {
            return collect();
        }

        return $batch->classSessions()
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('date')
            ->orderBy('start_time')
            ->take(30)
            ->get();
    }

    public function getHistoryProperty(): Collection
    {
        $batch = $this->batch;

        if (! $batch) {
            return collect();
        }

        $query = AttendanceModel::with('student', 'classSession')
            ->where('batch_id', $batch->id);

        if ($this->filterDate === 'today') {
            $query->whereDate('date', today());
        } elseif ($this->filterDate === 'week') {
            $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filterDate === 'month') {
            $query->where('date', '>=', now()->startOfMonth());
        }

        return $query->orderByDesc('date')->orderByDesc('created_at')->take(50)->get();
    }

    public function saveAttendance()
    {
        $session = $this->session;

        if (! $session) {
            session()->flash('trainer-attendance-error', 'Select a class session first to save attendance.');

            return;
        }

        $this->validate([
            'statuses' => ['required', 'array'],
            'statuses.*' => ['in:present,absent,late'],
        ]);

        $studentIds = $this->enrolledStudents->pluck('id')->map(fn ($id) => (string) $id);

        foreach ($this->statuses as $studentId => $status) {
            if (! $studentIds->contains($studentId)) {
                continue;
            }

            AttendanceModel::updateOrCreate(
                ['class_session_id' => $session->id, 'student_id' => (int) $studentId],
                [
                    'batch_id' => $session->batch_id,
                    'date' => $session->date->toDateString(),
                    'status' => $status,
                ]
            );
        }

        session()->flash('trainer-attendance-saved', 'Attendance saved for '.count($this->statuses).' student(s).');
    }

    public function getTodaySummaryProperty(): array
    {
        $batch = $this->batch;

        if (! $batch) {
            return ['present' => 0, 'late' => 0, 'absent' => 0];
        }

        return [
            'present' => AttendanceModel::where('batch_id', $batch->id)->whereDate('date', today())->where('status', 'present')->count(),
            'late' => AttendanceModel::where('batch_id', $batch->id)->whereDate('date', today())->where('status', 'late')->count(),
            'absent' => AttendanceModel::where('batch_id', $batch->id)->whereDate('date', today())->where('status', 'absent')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.trainer.attendance', [
            'sessions' => $this->sessions,
            'history' => $this->history,
            'summary' => $this->todaySummary,
        ]);
    }
}
