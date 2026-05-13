<?php

namespace Database\Factories;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    protected $model = StudentProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'school' => fake()->randomElement([
                'MIT', 'Stanford University', 'Harvard University',
                'UC Berkeley', 'Georgia Tech', 'University of Michigan',
                'Carnegie Mellon University', 'University of Texas at Austin',
                'UCLA', 'Columbia University', 'Cornell University',
                'University of Washington', 'Penn State', 'NYU',
            ]),
            'major' => fake()->randomElement([
                'Computer Science', 'Mechanical Engineering', 'Business Administration',
                'Biology', 'Psychology', 'Economics', 'Mathematics',
                'Electrical Engineering', 'Finance', 'Marketing',
                'Data Science', 'Information Systems', 'Chemistry',
            ]),
            'grad_year' => fake()->numberBetween(2024, 2028),
            'about' => fake()->optional(0.7)->paragraph(),
        ];
    }
}
