<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
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
}
