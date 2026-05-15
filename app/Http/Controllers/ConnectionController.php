<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function store(Request $request, StudentProfile $studentProfile)
    {
        $currentProfile = $request->user()->studentProfile;

        // Prevent self-connection
        if ($currentProfile->id === $studentProfile->id) {
            return back()->with('error', 'You cannot connect with yourself.');
        }

        // Check if the target already has a pending request to the current user
        $incomingPending = Connection::where('sender_id', $studentProfile->id)
            ->where('receiver_id', $currentProfile->id)
            ->where('status', 'pending')
            ->exists();

        if ($incomingPending) {
            return back()->with('error', 'This person already sent you a connection request. Check your incoming requests.');
        }

        // Check for existing connection row (sender -> receiver direction)
        $existing = Connection::where('sender_id', $currentProfile->id)
            ->where('receiver_id', $studentProfile->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return back()->with('error', 'You are already connected with this person.');
            }

            if ($existing->status === 'pending') {
                return back()->with('error', 'You already have a pending request to this person.');
            }

            // Status is 'rejected' — flip back to pending
            $existing->update(['status' => 'pending']);

            return back()->with('success', 'Connection request sent.');
        }

        // Check for existing accepted connection in reverse direction
        $reverseAccepted = Connection::where('sender_id', $studentProfile->id)
            ->where('receiver_id', $currentProfile->id)
            ->where('status', 'accepted')
            ->exists();

        if ($reverseAccepted) {
            return back()->with('error', 'You are already connected with this person.');
        }

        // Create new connection request
        Connection::create([
            'sender_id' => $currentProfile->id,
            'receiver_id' => $studentProfile->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Connection request sent.');
    }

    public function accept(Request $request, Connection $connection)
    {
        $currentProfile = $request->user()->studentProfile;

        // Only the receiver can accept
        if ($connection->receiver_id !== $currentProfile->id) {
            abort(403);
        }

        if ($connection->status !== 'pending') {
            return back()->with('error', 'This request can no longer be accepted.');
        }

        $connection->update(['status' => 'accepted']);

        return back()->with('success', 'Connection accepted.');
    }

    public function reject(Request $request, Connection $connection)
    {
        $currentProfile = $request->user()->studentProfile;

        // Only the receiver can reject
        if ($connection->receiver_id !== $currentProfile->id) {
            abort(403);
        }

        if ($connection->status !== 'pending') {
            return back()->with('error', 'This request can no longer be rejected.');
        }

        $connection->update(['status' => 'rejected']);

        return back()->with('success', 'Connection request rejected.');
    }

    public function destroy(Request $request, Connection $connection)
    {
        $currentProfile = $request->user()->studentProfile;

        // Sender can cancel their own pending request
        if ($connection->status === 'pending' && $connection->sender_id === $currentProfile->id) {
            $connection->delete();
            return back()->with('success', 'Connection request cancelled.');
        }

        // Either party can remove an accepted connection
        if ($connection->status === 'accepted' &&
            ($connection->sender_id === $currentProfile->id || $connection->receiver_id === $currentProfile->id)) {
            $connection->delete();
            return back()->with('success', 'Connection removed.');
        }

        abort(403);
    }
}
