<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function store(Request $request, JobPosting $jobPosting)
    {
        $studentProfile = Auth::user()->studentProfile;

        if (SavedJob::where('student_id', $studentProfile->id)->where('job_id', $jobPosting->id)->exists()) {
            return back()->with('error', 'You have already saved this job.');
        }

        SavedJob::create([
            'student_id' => $studentProfile->id,
            'job_id'     => $jobPosting->id,
        ]);

        return back()->with('success', 'Job saved successfully.');
    }

    public function destroy(Request $request, JobPosting $jobPosting)
    {
        $studentProfile = Auth::user()->studentProfile;

        $saved = SavedJob::where('student_id', $studentProfile->id)
            ->where('job_id', $jobPosting->id)
            ->firstOrFail();

        $saved->delete();

        return back()->with('success', 'Job removed from saved.');
    }

    public function index(Request $request)
    {
        $studentProfile = Auth::user()->studentProfile;

        $savedJobs    = $studentProfile->savedJobs()->with('job.employer')->latest()->get();
        $applications = $studentProfile->applications()->with('job.employer')->latest()->get();

        return view('jobs.my-jobs', compact('savedJobs', 'applications'));
    }
}
