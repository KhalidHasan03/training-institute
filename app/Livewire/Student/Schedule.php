<?php

namespace App\Livewire\Student;

use App\Models\ClassSession;

class Schedule extends StudentComponent
{
    public ?string $tab = 'upcoming';

    public function render()
    {
        $sessions = collect();

        if ($this->batch) {
            $query = ClassSession::query()->where('batch_id', $this->batch->id);

            $sessions = match ($this->tab) {
                'past' => (clone $query)->where('date', '<', today())->orderByDesc('date')->get(),
                default => (clone $query)->where('date', '>=', today())->orderBy('date')->orderBy('start_time')->get(),
            };
        }

        return view('livewire.student.schedule', compact('sessions'));
    }
}