<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Models\Connection;
use App\Models\JobPosting;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $profile = match ($user->account_type) {
            AccountType::Student => $user->studentProfile,
            AccountType::Employer => $user->employerProfile,
            default => null,
        };

        $recentJobs = null;
        $suggestedStudents = null;

        if ($user->account_type === AccountType::Student && $profile) {
            $recentJobs = JobPosting::with('employer')->latest()->take(4)->get();

            $excludedIds = Connection::where('sender_id', $profile->id)
                ->orWhere('receiver_id', $profile->id)
                ->get()
                ->flatMap(fn($c) => [$c->sender_id, $c->receiver_id])
                ->unique()
                ->filter(fn($id) => $id !== $profile->id);

            $suggestedStudents = StudentProfile::where('id', '!=', $profile->id)
                ->whereNotIn('id', $excludedIds)
                ->with('user')
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        return view('home', compact('profile', 'recentJobs', 'suggestedStudents'));
    }

    public function underConstruction()
    {
        return view('under-construction');
    }
}
