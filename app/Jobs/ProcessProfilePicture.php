<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessProfilePicture implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Model $profile,
        protected string $tempPath,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $localDisk = Storage::disk('local');
        $publicDisk = Storage::disk('public');

        if (! $localDisk->exists($this->tempPath)) {
            return;
        }

        $rawImage = $localDisk->get($this->tempPath);
        $source = @imagecreatefromstring($rawImage);

        if ($source === false) {
            $localDisk->delete($this->tempPath);
            return;
        }

        $originalWidth = imagesx($source);
        $originalHeight = imagesy($source);

        $size = 300;

        // Center-crop to square, then resize to 300x300
        $cropSize = min($originalWidth, $originalHeight);
        $cropX = (int) (($originalWidth - $cropSize) / 2);
        $cropY = (int) (($originalHeight - $cropSize) / 2);

        $destination = imagecreatetruecolor($size, $size);

        // Preserve quality: fill with white background for transparency
        $white = imagecolorallocate($destination, 255, 255, 255);
        imagefill($destination, 0, 0, $white);

        imagecopyresampled(
            $destination,
            $source,
            0, 0,
            $cropX, $cropY,
            $size, $size,
            $cropSize, $cropSize,
        );

        // Convert to JPEG
        ob_start();
        imagejpeg($destination, null, 90);
        $jpegData = ob_get_clean();

        imagedestroy($source);
        imagedestroy($destination);

        // Generate UUID filename and store
        $filename = Str::uuid() . '.jpg';
        $publicDisk->put('profile-pictures/' . $filename, $jpegData);

        // Delete old picture from disk
        $oldPicture = $this->profile->profile_picture;
        if ($oldPicture) {
            $publicDisk->delete('profile-pictures/' . $oldPicture);
        }

        // Update profile record
        $this->profile->update(['profile_picture' => $filename]);

        // Delete temp file
        $localDisk->delete($this->tempPath);
    }
}
