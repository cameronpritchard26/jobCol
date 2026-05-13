<?php

namespace Database\Factories;

use App\Models\ExperienceEntry;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExperienceEntry>
 */
class ExperienceEntryFactory extends Factory
{
    protected $model = ExperienceEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startMonth = fake()->numberBetween(1, 12);
        $startYear = fake()->numberBetween(2018, 2024);
        $isCurrent = fake()->boolean(30);

        return [
            'student_profile_id' => StudentProfile::factory(),
            'title' => fake()->randomElement([
                'Software Engineering Intern', 'Data Analyst Intern', 'Marketing Intern',
                'Research Assistant', 'Teaching Assistant', 'Product Management Intern',
                'UX Design Intern', 'Business Analyst Intern', 'QA Engineering Intern',
                'Full Stack Developer Intern', 'Machine Learning Intern', 'Sales Intern',
            ]),
            'company' => fake()->randomElement([
                'Google', 'Amazon', 'Microsoft', 'Apple', 'Meta', 'Netflix',
                'Spotify', 'Salesforce', 'Adobe', 'IBM', 'Intel', 'Cisco',
                'Deloitte', 'McKinsey', 'Goldman Sachs', 'JPMorgan Chase',
            ]),
            'start_month' => $startMonth,
            'start_year' => $startYear,
            'end_month' => $isCurrent ? null : fake()->numberBetween(1, 12),
            'end_year' => $isCurrent ? null : $startYear + fake()->numberBetween(0, 2),
            'description' => fake()->optional(0.7)->sentence(15),
        ];
    }
}
