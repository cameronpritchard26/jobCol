<?php

namespace App\Http\Controllers;

use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->employerProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        return view('profile.employer.show', compact('profile'));
    }

    public function create()
    {
        if (Auth::user()->employerProfile) {
            return redirect()->route('profile.show');
        }

        return view('profile.employer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->employerProfile()->create($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile created successfully.');
    }

    public function edit()
    {
        $profile = Auth::user()->employerProfile;

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        return view('profile.employer.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->employerProfile->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
