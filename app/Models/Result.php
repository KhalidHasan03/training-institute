<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'marks',
        'grade',
        'remarks',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getPercentageAttribute(): float
    {
        $total = (float) $this->exam?->total_marks ?: 0;

        return $total > 0 ? round(((float) $this->marks / $total) * 100, 1) : 0;
    }
}
