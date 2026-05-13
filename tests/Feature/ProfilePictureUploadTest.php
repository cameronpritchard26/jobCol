<?php

use App\Jobs\DeleteProfilePicture;
use App\Jobs\ProcessProfilePicture;
use App\Models\EmployerProfile;
use App\Models\StudentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('student can upload a profile picture', function () {
    Bus::fake();

    $profile = StudentProfile::factory()->create();

    $response = $this->actingAs($profile->user)
        ->post(route('profile.picture.update'), [
            'profile_picture' => UploadedFile::fake()->image('avatar.png', 600, 600),
        ]);

    $response->assertRedirect(route('profile.show'));
    $response->assertSessionHas('success');

    Bus::assertDispatched(ProcessProfilePicture::class);
});

test('employer can upload a profile picture', function () {
    Bus::fake();

    $profile = EmployerProfile::factory()->create();

    $response = $this->actingAs($profile->user)
        ->post(route('profile.picture.update'), [
            'profile_picture' => UploadedFile::fake()->image('logo.jpg', 800, 800),
        ]);

    $response->assertRedirect(route('profile.show'));
    $response->assertSessionHas('success');

    Bus::assertDispatched(ProcessProfilePicture::class);
});

test('upload validation rejects non-image files', function () {
    $profile = StudentProfile::factory()->create();

    $response = $this->actingAs($profile->user)
        ->post(route('profile.picture.update'), [
            'profile_picture' => UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ]);

    $response->assertSessionHasErrors('profile_picture');
});

test('upload validation rejects files over 5MB', function () {
    $profile = StudentProfile::factory()->create();

    $response = $this->actingAs($profile->user)
        ->post(route('profile.picture.update'), [
            'profile_picture' => UploadedFile::fake()->image('huge.jpg')->size(6000),
        ]);

    $response->assertSessionHasErrors('profile_picture');
});

test('ProcessProfilePicture job resizes converts and stores image', function () {
    Storage::fake('local');
    Storage::fake('public');

    $profile = StudentProfile::factory()->create();

    // Create a real GD image for the job to process
    $image = imagecreatetruecolor(600, 400);
    $color = imagecolorallocate($image, 100, 150, 200);
    imagefill($image, 0, 0, $color);
    ob_start();
    imagepng($image);
    $pngData = ob_get_clean();
    imagedestroy($image);

    $tempPath = 'temp/profile-pictures/test-upload.png';
    Storage::disk('local')->put($tempPath, $pngData);

    $job = new ProcessProfilePicture($profile, $tempPath);
    $job->handle();

    $profile->refresh();

    // Profile picture should be set to a UUID.jpg filename
    expect($profile->profile_picture)->not->toBeNull();
    expect($profile->profile_picture)->toEndWith('.jpg');

    // File should exist on the public disk
    Storage::disk('public')->assertExists('profile-pictures/' . $profile->profile_picture);

    // Temp file should be cleaned up
    Storage::disk('local')->assertMissing($tempPath);

    // Verify the stored image is 300x300 JPEG
    $storedData = Storage::disk('public')->get('profile-pictures/' . $profile->profile_picture);
    $storedImage = imagecreatefromstring($storedData);
    expect(imagesx($storedImage))->toBe(300);
    expect(imagesy($storedImage))->toBe(300);
    imagedestroy($storedImage);
});

test('ProcessProfilePicture job deletes old picture when uploading new one', function () {
    Storage::fake('local');
    Storage::fake('public');

    $profile = StudentProfile::factory()->create([
        'profile_picture' => 'old-picture.jpg',
    ]);

    // Place the old picture on disk
    Storage::disk('public')->put('profile-pictures/old-picture.jpg', 'old-data');

    // Create a real GD image for the job to process
    $image = imagecreatetruecolor(200, 200);
    ob_start();
    imagejpeg($image);
    $jpgData = ob_get_clean();
    imagedestroy($image);

    $tempPath = 'temp/profile-pictures/new-upload.jpg';
    Storage::disk('local')->put($tempPath, $jpgData);

    $job = new ProcessProfilePicture($profile, $tempPath);
    $job->handle();

    // Old picture should be deleted
    Storage::disk('public')->assertMissing('profile-pictures/old-picture.jpg');

    // New picture should exist
    $profile->refresh();
    Storage::disk('public')->assertExists('profile-pictures/' . $profile->profile_picture);
});

test('DeleteProfilePicture job deletes file and nulls column', function () {
    Storage::fake('public');

    $profile = EmployerProfile::factory()->create([
        'profile_picture' => 'to-delete.jpg',
    ]);

    Storage::disk('public')->put('profile-pictures/to-delete.jpg', 'image-data');

    $job = new DeleteProfilePicture($profile);
    $job->handle();

    Storage::disk('public')->assertMissing('profile-pictures/to-delete.jpg');

    $profile->refresh();
    expect($profile->profile_picture)->toBeNull();
});

test('student can delete their profile picture', function () {
    Bus::fake();

    $profile = StudentProfile::factory()->create([
        'profile_picture' => 'some-picture.jpg',
    ]);

    $response = $this->actingAs($profile->user)
        ->delete(route('profile.picture.destroy'));

    $response->assertRedirect(route('profile.show'));
    $response->assertSessionHas('success');

    Bus::assertDispatched(DeleteProfilePicture::class);
});

test('employer can delete their profile picture', function () {
    Bus::fake();

    $profile = EmployerProfile::factory()->create([
        'profile_picture' => 'company-logo.jpg',
    ]);

    $response = $this->actingAs($profile->user)
        ->delete(route('profile.picture.destroy'));

    $response->assertRedirect(route('profile.show'));
    $response->assertSessionHas('success');

    Bus::assertDispatched(DeleteProfilePicture::class);
});
