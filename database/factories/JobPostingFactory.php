<?php

namespace Database\Factories;

use App\Enums\JobType;
use App\Enums\SalaryType;
use App\Models\EmployerProfile;
use App\Models\JobPosting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobPosting>
 */
class JobPostingFactory extends Factory
{
    protected $model = JobPosting::class;

    public function definition(): array
    {
        return [
            'employer_id'  => EmployerProfile::factory(),
            'title'        => fake()->jobTitle(),
            'description'  => fake()->paragraphs(3, true),
            'location'     => fake()->city() . ', ' . fake()->stateAbbr(),
            'job_type'     => fake()->randomElement(JobType::cases())->value,
            'salary'       => fake()->numberBetween(30, 150) * 1000,
            'salary_type'  => fake()->randomElement(SalaryType::cases())->value,
            'deadline'     => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }
}
