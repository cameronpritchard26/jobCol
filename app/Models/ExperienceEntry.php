<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperienceEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_profile_id',
        'title',
        'company',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'description',
    ];

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
