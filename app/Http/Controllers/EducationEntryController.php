<?php

namespace App\Http\Controllers;

use App\Models\EducationEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationEntryController extends Controller
{
    public function create()
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        if ($profile->educationEntries()->count() >= 3) {
            return redirect()->route('profile.show')
                ->with('error', 'You can only have up to 3 education entries.');
        }

        return view('profile.student.education.create');
    }

    public function store(Request $request)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        if ($profile->educationEntries()->count() >= 3) {
            return redirect()->route('profile.show')
                ->with('error', 'You can only have up to 3 education entries.');
        }

        $validated = $request->validate([
            'degree' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_year' => 'required|integer|min:1900|max:2100',
        ]);

        $profile->educationEntries()->create($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Education entry added successfully.');
    }

    public function edit(EducationEntry $entry)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile || $entry->student_profile_id !== $profile->id) {
            abort(403);
        }

        return view('profile.student.education.edit', compact('entry'));
    }

    public function update(Request $request, EducationEntry $entry)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile || $entry->student_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'degree' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:2100',
            'end_year' => 'required|integer|min:1900|max:2100',
        ]);

        $entry->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Education entry updated successfully.');
    }

    public function destroy(EducationEntry $entry)
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile || $entry->student_profile_id !== $profile->id) {
            abort(403);
        }

        $entry->delete();

        return redirect()->route('profile.show')
            ->with('success', 'Education entry removed.');
    }
}
