<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'school',
        'major',
        'grad_year',
        'about',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educationEntries(): HasMany
    {
        return $this->hasMany(EducationEntry::class);
    }

    public function experienceEntries(): HasMany
    {
        return $this->hasMany(ExperienceEntry::class);
    }
}
