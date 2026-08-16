<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'short_description',
        'description',
        'duration',
        'fee',
        'level',
        'status',
        'highlights',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'highlights' => 'array',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function activeBatches(): HasMany
    {
        return $this->hasMany(Batch::class)->where('status', 'active');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}
