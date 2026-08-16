<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'title',
        'description',
        'attachment',
        'total_marks',
        'deadline',
        'status',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'deadline' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline && $this->deadline->isPast() && $this->status === 'active';
    }
}
