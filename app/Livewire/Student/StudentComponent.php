<?php

namespace App\Livewire\Student;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
abstract class StudentComponent extends Component
{
    public ?int $batchId = null;

    public function getStudentProperty(): ?Student
    {
        return auth()->user()->student;
    }

    public function getActiveEnrollmentsProperty(): Collection
    {
        $student = $this->student;

        if (! $student) {
            return collect();
        }

        return $student->enrollments()
            ->with(['batch.course', 'batch.trainer'])
            ->where('status', 'active')
            ->orderBy('enrollment_date')
            ->get();
    }

    public function resolveBatchId(): ?int
    {
        $active = $this->activeEnrollments;

        if ($active->isEmpty()) {
            return null;
        }

        $requested = (int) request()->query('batch');
        $selected = $this->batchId ?: session('student_batch_id');

        $candidate = $requested ?: $selected;

        if ($candidate && $active->firstWhere('batch_id', (int) $candidate)) {
            if ($requested) {
                session(['student_batch_id' => (int) $candidate]);
            }

            return (int) $candidate;
        }

        return $active->first()->batch_id;
    }

    public function getEnrollmentProperty(): ?Enrollment
    {
        $id = $this->resolveBatchId();

        return $id ? $this->activeEnrollments->firstWhere('batch_id', $id) : null;
    }

    public function getBatchProperty(): ?Batch
    {
        return $this->enrollment?->batch;
    }

    public function updatedBatchId(): void
    {
        $active = $this->activeEnrollments;

        if ($this->batchId && $active->firstWhere('batch_id', $this->batchId)) {
            session(['student_batch_id' => $this->batchId]);
        }
    }

    public function switchBatch(int $batchId): void
    {
        $this->batchId = $batchId;
        $this->updatedBatchId();
    }

    public function getProgressProperty(): int
    {
        $batch = $this->batch;
        if (! $batch) {
            return 0;
        }

        $daysRange = max(1, (int) $batch->start_date->diffInDays($batch->end_date));
        $elapsed = max(0, (int) $batch->start_date->diffInDays(today()));

        return min(100, (int) round(($elapsed / $daysRange) * 100));
    }

    public function getAttendancePercentageProperty(): int
    {
        $student = $this->student;
        $batch = $this->batch;

        if (! $student || ! $batch) {
            return 0;
        }

        $total = Attendance::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->count();

        if ($total === 0) {
            return 0;
        }

        $present = Attendance::where('student_id', $student->id)
            ->where('batch_id', $batch->id)
            ->whereIn('status', ['present', 'late'])
            ->count();

        return (int) round(($present / $total) * 100);
    }

    public function getNextClassProperty(): ?ClassSession
    {
        $batch = $this->batch;

        return $batch?->classSessions()
            ->where('date', '>=', today())
            ->where('status', 'scheduled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();
    }

    public function getPendingDueProperty(): float
    {
        return $this->enrollment?->due ?? 0;
    }

    public function getPendingAssignmentsProperty(): int
    {
        $batch = $this->batch;

        if (! $batch) {
            return 0;
        }

        return $batch->assignments()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now());
            })
            ->count();
    }

    public function getAnnouncementsProperty()
    {
        return Announcement::published()
            ->whereIn('audience', ['all', 'students'])
            ->latest('published_at')
            ->take(5)
            ->get();
    }
}
