<?php

namespace App\Http\Controllers;

use App\Models\EmployerProfile;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $type = $request->input('type', 'all');

        $students = collect();
        $employers = collect();

        if ($q) {
            $search = mb_strtolower($q);

            if ($type === 'all' || $type === 'students') {
                $students = StudentProfile::where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                          ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"]);
                })->with('user')->get();
            }

            if ($type === 'all' || $type === 'employers') {
                $employers = EmployerProfile::whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->with('user')->get();
            }
        }

        return view('network.index', compact('students', 'employers', 'q', 'type'));
    }

    public function showStudent(StudentProfile $studentProfile)
    {
        $studentProfile->load(['educationEntries', 'experienceEntries', 'user']);

        return view('profile.student.public', ['profile' => $studentProfile]);
    }

    public function showEmployer(EmployerProfile $employerProfile)
    {
        $employerProfile->load('user');

        return view('profile.employer.public', ['profile' => $employerProfile]);
    }
}
