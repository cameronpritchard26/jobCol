<?php

namespace Database\Seeders;

use App\Models\EducationEntry;
use App\Models\EmployerProfile;
use App\Models\ExperienceEntry;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $summary = "# Seeded Accounts\n\n";
        $summary .= "All accounts use password: `password`\n\n";

        // --- Students ---
        $summary .= "## Students (25)\n\n";

        for ($i = 1; $i <= 25; $i++) {
            if (User::where('username', "student_{$i}")->exists()) {
                continue;
            }

            $user = User::factory()->student()->create([
                'username' => "student_{$i}",
            ]);

            $profile = StudentProfile::factory()->create([
                'user_id' => $user->id,
            ]);

            $numEntries = fake()->numberBetween(1, 3);
            $entries = EducationEntry::factory($numEntries)->create([
                'student_profile_id' => $profile->id,
            ]);

            $summary .= "### student_{$i}\n";
            $summary .= "- **Username**: student_{$i}\n";
            $summary .= "- **Name**: {$profile->first_name} {$profile->last_name}\n";
            $summary .= "- **School**: {$profile->school}\n";
            $summary .= "- **Major**: {$profile->major}\n";
            $summary .= "- **Grad Year**: {$profile->grad_year}\n";
            $summary .= "- **About**: " . ($profile->about ?? '_none_') . "\n";
            $summary .= "- **Education Entries**:\n";

            foreach ($entries as $entry) {
                $summary .= "  - {$entry->degree} at {$entry->school} ({$entry->start_year}–{$entry->end_year})\n";
            }

            $numExperiences = fake()->numberBetween(1, 3);
            $experiences = ExperienceEntry::factory($numExperiences)->create([
                'student_profile_id' => $profile->id,
            ]);

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $summary .= "- **Experience Entries**:\n";

            foreach ($experiences as $exp) {
                $start = $months[$exp->start_month - 1] . " {$exp->start_year}";
                $end = $exp->end_month ? $months[$exp->end_month - 1] . " {$exp->end_year}" : 'Present';
                $summary .= "  - {$exp->title} at {$exp->company} ({$start} – {$end})\n";
            }

            $summary .= "\n";
        }

        // --- Employers ---
        $summary .= "## Employers (15)\n\n";

        for ($i = 1; $i <= 15; $i++) {
            if (User::where('username', "employer_{$i}")->exists()) {
                continue;
            }

            $user = User::factory()->employer()->create([
                'username' => "employer_{$i}",
            ]);

            $profile = EmployerProfile::factory()->create([
                'user_id' => $user->id,
            ]);

            $summary .= "### employer_{$i}\n";
            $summary .= "- **Username**: employer_{$i}\n";
            $summary .= "- **Company**: {$profile->name}\n";
            $summary .= "- **Industry**: {$profile->industry}\n";
            $summary .= "- **Location**: {$profile->location}\n";
            $summary .= "- **Website**: " . ($profile->website ?? '_none_') . "\n";
            $summary .= "- **Description**: " . ($profile->description ?? '_none_') . "\n";
            $summary .= "\n";
        }

        file_put_contents(storage_path('seeded_accounts.md'), $summary);
    }
}
