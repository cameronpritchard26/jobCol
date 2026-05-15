<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use App\Enums\JobType;
use App\Enums\SalaryType;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function index()
    {
        $jobPostings = JobPosting::with('employer')->latest()->paginate(12);
        $myEmployerProfileId = Auth::user()->account_type === AccountType::Employer
            ? Auth::user()->employerProfile?->id
            : null;

        return view('jobs.index', compact('jobPostings', 'myEmployerProfileId'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location'    => ['required', 'string', 'max:255'],
            'job_type'    => ['required', Rule::enum(JobType::class)],
            'salary'      => ['required', 'numeric', 'min:0'],
            'salary_type' => ['required', Rule::enum(SalaryType::class)],
            'deadline'    => ['required', 'date', 'after:today'],
        ]);

        Auth::user()->employerProfile->jobs()->create($validated);

        return redirect()->route('jobs.index')->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $jobPosting)
    {
        $jobPosting->load('employer');
        $isOwner = Auth::user()->account_type === AccountType::Employer
            && Auth::user()->employerProfile?->id === $jobPosting->employer_id;

        return view('jobs.show', compact('jobPosting', 'isOwner'));
    }

    public function edit(JobPosting $jobPosting)
    {
        $this->authorizeOwnership($jobPosting);

        return view('jobs.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        $this->authorizeOwnership($jobPosting);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location'    => ['required', 'string', 'max:255'],
            'job_type'    => ['required', Rule::enum(JobType::class)],
            'salary'      => ['required', 'numeric', 'min:0'],
            'salary_type' => ['required', Rule::enum(SalaryType::class)],
            'deadline'    => ['required', 'date', 'after:today'],
        ]);

        $jobPosting->update($validated);

        return redirect()->route('jobs.show', $jobPosting)->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $jobPosting)
    {
        $this->authorizeOwnership($jobPosting);
        $jobPosting->delete();

        return redirect()->route('jobs.index')->with('success', 'Job posting deleted.');
    }

    private function authorizeOwnership(JobPosting $jobPosting): void
    {
        if ($jobPosting->employer_id !== Auth::user()->employerProfile?->id) {
            abort(403);
        }
    }
}
