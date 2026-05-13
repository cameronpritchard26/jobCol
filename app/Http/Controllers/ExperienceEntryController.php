<?php

namespace App\Http\Controllers;

use App\Models\ExperienceEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceEntryController extends Controller
{
    public function create()
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        if ($profile->experienceEntries()->count() >= 3) {
            return redirect()->route('profile.show')
                ->with('error', 'You can only have up to 3 experience entries.');
        }

        return view('profile.student.experience.create');
    }

    public function store(Request $request)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        if ($profile->experienceEntries()->count() >= 3) {
            return redirect()->route('profile.show')
                ->with('error', 'You can only have up to 3 experience entries.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'start_month' => 'required|integer|min:1|max:12',
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_month' => 'nullable|integer|min:1|max:12|required_with:end_year',
            'end_year' => 'nullable|integer|min:1900|max:2100|required_with:end_month',
            'description' => 'nullable|string|max:1000',
        ]);

        $profile->experienceEntries()->create($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Experience entry added successfully.');
    }

    public function edit(ExperienceEntry $entry)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile || $entry->student_profile_id !== $profile->id) {
            abort(403);
        }

        return view('profile.student.experience.edit', compact('entry'));
    }

    public function update(Request $request, ExperienceEntry $entry)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile || $entry->student_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'start_month' => 'required|integer|min:1|max:12',
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_month' => 'nullable|integer|min:1|max:12|required_with:end_year',
            'end_year' => 'nullable|integer|min:1900|max:2100|required_with:end_month',
            'description' => 'nullable|string|max:1000',
        ]);

        $entry->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Experience entry updated successfully.');
    }

    public function destroy(ExperienceEntry $entry)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile || $entry->student_profile_id !== $profile->id) {
            abort(403);
        }

        $entry->delete();

        return redirect()->route('profile.show')
            ->with('success', 'Experience entry removed.');
    }
}
