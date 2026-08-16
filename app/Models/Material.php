<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'title',
        'description',
        'file_path',
        'external_url',
        'type',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
