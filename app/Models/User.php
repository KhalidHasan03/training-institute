<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo_path',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function trainer(): HasOne
    {
        return $this->hasOne(Trainer::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'published_by');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isTrainer(): bool
    {
        return $this->hasRole('trainer');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->roles->first()?->name ?? 'none';
    }

    public function hasBatchAccess(Batch $batch): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isTrainer() && $this->trainer) {
            return $batch->trainer_id === $this->trainer->id;
        }

        if ($this->isStudent() && $this->student) {
            return Enrollment::where('student_id', $this->student->id)
                ->where('batch_id', $batch->id)
                ->where('status', 'active')
                ->exists();
        }

        return false;
    }
}
