<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class DeleteProfilePicture implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Model $profile,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filename = $this->profile->profile_picture;

        if (! $filename) {
            return;
        }

        Storage::disk('public')->delete('profile-pictures/' . $filename);

        $this->profile->update(['profile_picture' => null]);
    }
}
