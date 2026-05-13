<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Jobs\DeleteProfilePicture;
use App\Jobs\ProcessProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    protected function profile()
    {
        $user = Auth::user();

        return match ($user->account_type) {
            AccountType::Student => $user->studentProfile,
            AccountType::Employer => $user->employerProfile,
            default => abort(403, 'Profile not available for this account type.'),
        };
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:5120',
        ]);

        $profile = $this->profile();

        if (! $profile) {
            return redirect()->route('profile.create')
                ->with('error', 'Please create a profile first.');
        }

        // Store the raw upload to a temp location on the local disk
        $tempPath = $request->file('profile_picture')
            ->store('temp/profile-pictures', 'local');

        ProcessProfilePicture::dispatch($profile, $tempPath);

        return redirect()->route('profile.show')
            ->with('success', 'Profile picture is being processed.');
    }

    public function status()
    {
        $profile = $this->profile();

        return response()->json([
            'profile_picture' => $profile?->profile_picture,
        ]);
    }

    public function destroy()
    {
        $profile = $this->profile();

        if (! $profile || ! $profile->profile_picture) {
            return redirect()->route('profile.show');
        }

        DeleteProfilePicture::dispatch($profile);

        return redirect()->route('profile.show')
            ->with('success', 'Profile picture is being removed.');
    }
}
