<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Models\Connection;
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

        $myConnections = collect();
        $pendingRequests = collect();
        $incomingRequests = collect();

        $user = $request->user();
        if ($user->account_type === AccountType::Student && $user->studentProfile) {
            $profileId = $user->studentProfile->id;

            $myConnections = $user->studentProfile->connections();

            $pendingRequests = Connection::where('sender_id', $profileId)
                ->where('status', 'pending')
                ->with('receiver')
                ->get();

            $incomingRequests = Connection::where('receiver_id', $profileId)
                ->where('status', 'pending')
                ->with('sender')
                ->get();
        }

        return view('network.index', compact(
            'students', 'employers', 'q', 'type',
            'myConnections', 'pendingRequests', 'incomingRequests'
        ));
    }

    public function showStudent(StudentProfile $studentProfile)
    {
        $studentProfile->load(['educationEntries', 'experienceEntries', 'user']);

        $connectionStatus = null;
        $connection = null;
        $user = request()->user();

        if ($user->account_type === AccountType::Student && $user->studentProfile) {
            $currentProfileId = $user->studentProfile->id;
            $viewedProfileId = $studentProfile->id;

            if ($currentProfileId !== $viewedProfileId) {
                // Check sender -> receiver direction
                $conn = Connection::where('sender_id', $currentProfileId)
                    ->where('receiver_id', $viewedProfileId)
                    ->first();

                if ($conn) {
                    $connection = $conn;
                    $connectionStatus = $conn->status === 'accepted' ? 'connected' : ($conn->status === 'pending' ? 'pending_sent' : null);
                }

                // Check receiver -> sender direction
                if (!$conn) {
                    $conn = Connection::where('sender_id', $viewedProfileId)
                        ->where('receiver_id', $currentProfileId)
                        ->first();

                    if ($conn) {
                        $connection = $conn;
                        $connectionStatus = $conn->status === 'accepted' ? 'connected' : ($conn->status === 'pending' ? 'pending_received' : null);
                    }
                }
            }
        }

        return view('profile.student.public', [
            'profile' => $studentProfile,
            'connectionStatus' => $connectionStatus,
            'connection' => $connection,
        ]);
    }

    public function showEmployer(EmployerProfile $employerProfile)
    {
        $employerProfile->load('user');

        return view('profile.employer.public', ['profile' => $employerProfile]);
    }
}
