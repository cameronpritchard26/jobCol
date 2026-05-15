<?php

namespace Database\Seeders;

use App\Models\EmployerProfile;
use App\Models\JobPosting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $employers = EmployerProfile::with('user')->get();

        if ($employers->isEmpty()) {
            $this->command->warn('No employer profiles found. Run DatabaseSeeder first.');
            return;
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $summary  = "# Seeded Job Postings\n\n";
        $summary .= "Generated: " . now()->toDateTimeString() . "\n\n";

        $totalCreated = 0;

        foreach ($employers as $employer) {
            $count    = fake()->numberBetween(2, 4);
            $postings = JobPosting::factory($count)->create(['employer_id' => $employer->id]);

            $summary .= "## {$employer->name} (`{$employer->user->username}`)\n\n";

            foreach ($postings as $job) {
                $deadline     = $job->deadline->format('M d, Y');
                $salaryFormatted = '$' . number_format($job->salary, 2) . ' / ' . $job->salary_type->label();

                $summary .= "### {$job->title}\n";
                $summary .= "- **Location**: {$job->location}\n";
                $summary .= "- **Type**: {$job->job_type->label()}\n";
                $summary .= "- **Salary**: {$salaryFormatted}\n";
                $summary .= "- **Deadline**: {$deadline}\n";
                $summary .= "- **Description**: {$job->description}\n";
                $summary .= "\n";

                $totalCreated++;
            }
        }

        $summary .= "---\n**Total postings created: {$totalCreated}**\n";

        file_put_contents(storage_path('seeded_job_postings.md'), $summary);

        $this->command->info("Created {$totalCreated} job postings across {$employers->count()} employers.");
        $this->command->info('Written to storage/seeded_job_postings.md');
    }
}
