<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile) {
            return redirect()->route('student.profile.create');
        }

        return view('profile.student.show', compact('profile'));
    }

    public function create()
    {
        if (Auth::user()->studentProfile) {
            return redirect()->route('student.profile.show');
        }

        return view('profile.student.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'grad_year' => 'required|integer|min:1900|max:2100',
            'about' => 'nullable|string|max:1000',
        ]);

        Auth::user()->studentProfile()->create($validated);

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile created successfully.');
    }

    public function edit()
    {
        $profile = Auth::user()->studentProfile;

        if (! $profile) {
            return redirect()->route('student.profile.create');
        }

        return view('profile.student.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'grad_year' => 'required|integer|min:1900|max:2100',
            'about' => 'nullable|string|max:1000',
        ]);

        Auth::user()->studentProfile->update($validated);

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
