<?php

namespace App\Livewire\Trainer;

use App\Models\ClassSession;

class Sessions extends TrainerComponent
{
    public string $tab = 'upcoming';

    public string $statusFilter = 'all';

    public bool $showCreate = false;

    public bool $showEdit = false;

    public ?int $editingId = null;

    public $formDate;

    public $formStartTime;

    public $formEndTime;

    public ?string $formTopic = null;

    public ?string $formRoom = null;

    public ?string $formNotes = null;

    public string $formStatus = 'scheduled';

    public bool $saved = false;

    public function openCreate()
    {
        $this->resetForm();
        $this->showEdit = false;
        $this->showCreate = true;
        $this->formDate = now()->toDateString();
    }

    public function openEdit(int $sessionId)
    {
        $session = ClassSession::with('batch')
            ->whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
            ->findOrFail($sessionId);

        $this->editingId = $session->id;
        $this->formDate = $session->date->toDateString();
        $this->formStartTime = $session->start_time?->format('H:i');
        $this->formEndTime = $session->end_time?->format('H:i');
        $this->formTopic = $session->topic;
        $this->formRoom = $session->room;
        $this->formNotes = $session->notes;
        $this->formStatus = $session->status;

        $this->showCreate = false;
        $this->showEdit = true;
    }

    public function closeForm(): void
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function save()
    {
        $validated = $this->validate([
            'formDate' => ['required', 'date'],
            'formStartTime' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'formEndTime' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'formTopic' => ['nullable', 'string', 'max:255'],
            'formRoom' => ['nullable', 'string', 'max:255'],
            'formNotes' => ['nullable', 'string'],
            'formStatus' => ['required', 'in:scheduled,completed,cancelled'],
        ]);

        $batch = $this->batch;

        if (! $batch) {
            session()->flash('trainer-session-error', 'Select a batch first to manage its sessions.');

            return;
        }

        $data = [
            'batch_id' => $batch->id,
            'trainer_id' => $batch->trainer_id,
            'date' => $validated['formDate'],
            'start_time' => $validated['formStartTime'],
            'end_time' => $validated['formEndTime'],
            'topic' => $validated['formTopic'],
            'room' => $validated['formRoom'],
            'notes' => $validated['formNotes'],
            'status' => $validated['formStatus'],
        ];

        if ($this->editingId) {
            $session = ClassSession::whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
                ->findOrFail($this->editingId);
            $session->update($data);
        } else {
            $batch->classSessions()->create($data);
        }

        $this->saved = true;
        session()->flash('trainer-session-saved', $this->editingId ? 'Session updated successfully.' : 'Session created successfully.');
        $this->closeForm();
    }

    public function toggleStatus(int $sessionId, string $status): void
    {
        if (! in_array($status, ['scheduled', 'completed', 'cancelled'], true)) {
            return;
        }

        $session = ClassSession::whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
            ->findOrFail($sessionId);

        $session->update(['status' => $status]);
    }

    public function delete(int $sessionId): void
    {
        $session = ClassSession::whereHas('batch', fn ($q) => $q->where('trainer_id', $this->trainer->id))
            ->findOrFail($sessionId);

        $session->delete();

        session()->flash('trainer-session-saved', 'Session deleted successfully.');
    }

    public function getSessionsProperty()
    {
        $batch = $this->batch;

        if (! $batch) {
            return collect();
        }

        $query = ClassSession::query()->where('batch_id', $batch->id);

        if ($this->statusFilter === 'scheduled') {
            $query->where('status', 'scheduled');
        } elseif ($this->statusFilter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($this->statusFilter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        return match ($this->tab) {
            'past' => (clone $query)->where('date', '<', today())->orderByDesc('date')->get(),
            default => (clone $query)->where('date', '>=', today())->orderBy('date')->orderBy('start_time')->get(),
        };
    }

    public function resetForm(): void
    {
        $this->formDate = null;
        $this->formStartTime = null;
        $this->formEndTime = null;
        $this->formTopic = null;
        $this->formRoom = null;
        $this->formNotes = null;
        $this->formStatus = 'scheduled';
    }

    public function render()
    {
        return view('livewire.trainer.sessions', [
            'sessions' => $this->sessions,
        ]);
    }
}
