<?php

namespace App\Models;

use App\Enums\JobType;
use App\Enums\SalaryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPosting extends Model
{
    use HasFactory;
    protected $table = 'job_postings';

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'job_type',
        'salary',
        'salary_type',
        'deadline',
    ];

    protected $casts = [
        'job_type'    => JobType::class,
        'salary_type' => SalaryType::class,
        'deadline'    => 'date',
        'salary'      => 'decimal:2',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(EmployerProfile::class, 'employer_id');
    }
}
