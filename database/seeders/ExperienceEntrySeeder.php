<?php

namespace Database\Seeders;

use App\Models\ExperienceEntry;
use App\Models\StudentProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExperienceEntrySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $profiles = StudentProfile::doesntHave('experienceEntries')->get();

        foreach ($profiles as $profile) {
            $numEntries = fake()->numberBetween(1, 3);
            ExperienceEntry::factory($numEntries)->create([
                'student_profile_id' => $profile->id,
            ]);
        }

        $this->command->info("Added experience entries to {$profiles->count()} student profiles.");
    }
}