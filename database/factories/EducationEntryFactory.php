<?php

namespace Database\Factories;

use App\Models\EducationEntry;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EducationEntry>
 */
class EducationEntryFactory extends Factory
{
    protected $model = EducationEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startYear = fake()->numberBetween(2018, 2024);

        return [
            'student_profile_id' => StudentProfile::factory(),
            'degree' => fake()->randomElement([
                'Bachelor of Science', 'Bachelor of Arts', 'Master of Science',
                'Master of Arts', 'Associate of Science', 'Associate of Arts',
                'Master of Business Administration', 'Doctor of Philosophy',
            ]),
            'school' => fake()->randomElement([
                'MIT', 'Stanford University', 'Harvard University',
                'UC Berkeley', 'Georgia Tech', 'University of Michigan',
                'Carnegie Mellon University', 'University of Texas at Austin',
                'UCLA', 'Columbia University', 'Cornell University',
                'University of Washington', 'Penn State', 'NYU',
            ]),
            'start_year' => $startYear,
            'end_year' => $startYear + fake()->numberBetween(2, 4),
        ];
    }
}
