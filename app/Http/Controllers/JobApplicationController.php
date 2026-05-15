<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    public function store(Request $request, JobPosting $jobPosting)
    {
        $studentProfile = Auth::user()->studentProfile;

        if (JobApplication::where('student_id', $studentProfile->id)->where('job_id', $jobPosting->id)->exists()) {
            return back()->with('error', 'You have already applied for this position.');
        }

        JobApplication::create([
            'student_id' => $studentProfile->id,
            'job_id'     => $jobPosting->id,
            'status'     => ApplicationStatus::Submitted,
        ]);

        SavedJob::where('student_id', $studentProfile->id)->where('job_id', $jobPosting->id)->delete();

        return redirect()->route('jobs.show', $jobPosting)->with('success', 'Application submitted successfully.');
    }

    public function destroy(Request $request, JobPosting $jobPosting)
    {
        $studentProfile = Auth::user()->studentProfile;

        $application = JobApplication::where('student_id', $studentProfile->id)
            ->where('job_id', $jobPosting->id)
            ->firstOrFail();

        if ($application->status !== ApplicationStatus::Submitted) {
            return back()->with('error', 'You cannot withdraw an application that has already been reviewed.');
        }

        $application->delete();

        return back()->with('success', 'Application withdrawn.');
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        if ($application->job->employer_id !== Auth::user()->employerProfile?->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
        ]);

        if ($validated['status'] === ApplicationStatus::Submitted->value) {
            return back()->with('error', 'Cannot revert an application to submitted.');
        }

        $application->update(['status' => $validated['status']]);

        return back()->with('success', 'Application status updated.');
    }

    public function indexForJob(Request $request, JobPosting $jobPosting)
    {
        if ($jobPosting->employer_id !== Auth::user()->employerProfile?->id) {
            abort(403);
        }

        $applications = $jobPosting->applications()->with('student.user')->latest()->get();

        return view('jobs.applications', compact('jobPosting', 'applications'));
    }
}
