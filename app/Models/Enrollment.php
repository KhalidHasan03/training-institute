<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'enrollment_date',
        'course_fee',
        'discount',
        'final_fee',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'course_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_fee' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAttribute(): float
    {
        return (float) $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function getDueAttribute(): float
    {
        return max(0, (float) $this->final_fee - $this->paid);
    }
}
