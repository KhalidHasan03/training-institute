<?php

namespace App\Livewire\Trainer;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\Exam;

class Dashboard extends TrainerComponent
{
    public function getActiveBatchCountProperty(): int
    {
        return $this->batches->where('status', 'active')->count();
    }

    public function getTotalStudentsProperty(): int
    {
        return $this->activeEnrollments->unique('student_id')->count();
    }

    public function getActiveEnrollmentsProperty()
    {
        $batchIds = $this->batches->pluck('id');

        if ($batchIds->isEmpty()) {
            return collect();
        }

        return Enrollment::with('student', 'batch.course')
            ->whereIn('batch_id', $batchIds)
            ->where('status', 'active')
            ->get();
    }

    public function getAttendanceRateProperty(): int
    {
        $batchIds = $this->batches->pluck('id');

        if ($batchIds->isEmpty()) {
            return 0;
        }

        $total = Attendance::whereIn('batch_id', $batchIds)->count();

        if ($total === 0) {
            return 0;
        }

        $present = Attendance::whereIn('batch_id', $batchIds)
            ->whereIn('status', ['present', 'late'])
            ->count();

        return (int) round(($present / $total) * 100);
    }

    public function getExamCountProperty(): int
    {
        $batchIds = $this->batches->pluck('id');

        if ($batchIds->isEmpty()) {
            return 0;
        }

        return Exam::whereIn('batch_id', $batchIds)->count();
    }

    public function getUpcomingAllSessionsProperty()
    {
        $batchIds = $this->batches->pluck('id');

        if ($batchIds->isEmpty()) {
            return collect();
        }

        return ClassSession::with('batch.course')
            ->whereIn('batch_id', $batchIds)
            ->where('date', '>=', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
    }

    public function getTodaySessionsProperty()
    {
        $batchIds = $this->batches->pluck('id');

        if ($batchIds->isEmpty()) {
            return collect();
        }

        return ClassSession::with('batch.course')
            ->whereIn('batch_id', $batchIds)
            ->whereDate('date', today())
            ->orderBy('start_time')
            ->get();
    }

    public function getAnnouncementsProperty()
    {
        return Announcement::published()
            ->whereIn('audience', ['all', 'trainers'])
            ->latest('published_at')
            ->take(5)
            ->get();
    }

    public function render()
    {
        $recentEnrollments = $this->activeEnrollments
            ->sortByDesc('enrollment_date')
            ->take(5);

        return view('livewire.trainer.dashboard', [
            'recentEnrollments' => $recentEnrollments,
        ]);
    }
}
