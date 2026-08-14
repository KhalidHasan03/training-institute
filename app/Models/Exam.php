<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'title',
        'exam_date',
        'total_marks',
        'description',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'total_marks' => 'decimal:2',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}