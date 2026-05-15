<?php

namespace Database\Factories;

use App\Models\JobPosting;
use App\Models\SavedJob;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavedJob>
 */
class SavedJobFactory extends Factory
{
    protected $model = SavedJob::class;

    public function definition(): array
    {
        return [
            'student_id' => StudentProfile::factory(),
            'job_id'     => JobPosting::factory(),
        ];
    }
}
