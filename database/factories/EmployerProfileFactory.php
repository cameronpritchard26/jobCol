<?php

namespace Database\Factories;

use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmployerProfile>
 */
class EmployerProfileFactory extends Factory
{
    protected $model = EmployerProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->employer(),
            'name' => fake()->company(),
            'industry' => fake()->randomElement([
                'Technology', 'Healthcare', 'Finance', 'Education',
                'Manufacturing', 'Retail', 'Consulting', 'Media',
                'Energy', 'Real Estate', 'Telecommunications', 'Aerospace',
            ]),
            'location' => fake()->city() . ', ' . fake()->stateAbbr(),
            'website' => fake()->optional(0.8)->url(),
            'description' => fake()->optional(0.7)->paragraph(),
        ];
    }
}
