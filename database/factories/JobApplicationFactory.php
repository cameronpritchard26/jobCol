<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition(): array
    {
        return [
            'student_id' => StudentProfile::factory(),
            'job_id'     => JobPosting::factory(),
            'status'     => ApplicationStatus::Submitted->value,
        ];
    }

    public function accepted(): static
    {
        return $this->state(['status' => ApplicationStatus::Accepted->value]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => ApplicationStatus::Rejected->value]);
    }
}
