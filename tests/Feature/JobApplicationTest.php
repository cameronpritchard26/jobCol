<?php

use App\Enums\ApplicationStatus;
use App\Models\EmployerProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SavedJob;
use App\Models\StudentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function makeStudentWithJob(): array
{
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create();
    return [$student, $job];
}

// ---------------------------------------------------------------------------
// Saved Jobs — saving
// ---------------------------------------------------------------------------

test('student can save a job', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->post(route('jobs.save', $job))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(SavedJob::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeTrue();
});

test('saving a job creates exactly one database record', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)->post(route('jobs.save', $job));

    expect(SavedJob::count())->toBe(1);
});

test('student cannot save the same job twice', function () {
    [$student, $job] = makeStudentWithJob();

    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->post(route('jobs.save', $job))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(SavedJob::count())->toBe(1);
});

test('employer cannot save a job', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.save', $job))
        ->assertForbidden();
});

test('guest cannot save a job', function () {
    $job = JobPosting::factory()->create();

    $this->post(route('jobs.save', $job))->assertRedirect(route('login'));
});

// ---------------------------------------------------------------------------
// Saved Jobs — unsaving
// ---------------------------------------------------------------------------

test('student can unsave a job', function () {
    [$student, $job] = makeStudentWithJob();
    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->delete(route('jobs.unsave', $job))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(SavedJob::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeFalse();
});

test('unsaving a job removes it from the database', function () {
    [$student, $job] = makeStudentWithJob();
    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)->delete(route('jobs.unsave', $job));

    expect(SavedJob::count())->toBe(0);
});

test('student cannot unsave a job they did not save', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->delete(route('jobs.unsave', $job))
        ->assertNotFound();
});

test('employer cannot unsave a job', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create();

    $this->actingAs($employer->user)
        ->delete(route('jobs.unsave', $job))
        ->assertForbidden();
});

// ---------------------------------------------------------------------------
// Job Applications — applying
// ---------------------------------------------------------------------------

test('student can apply to a job', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->post(route('jobs.apply', $job))
        ->assertRedirect(route('jobs.show', $job))
        ->assertSessionHas('success');

    expect(JobApplication::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeTrue();
});

test('application is created with submitted status', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)->post(route('jobs.apply', $job));

    $application = JobApplication::where('student_id', $student->id)->where('job_id', $job->id)->first();
    expect($application->status)->toBe(ApplicationStatus::Submitted);
});

test('student cannot apply to the same job twice', function () {
    [$student, $job] = makeStudentWithJob();

    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->post(route('jobs.apply', $job))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(JobApplication::count())->toBe(1);
});

test('employer cannot apply to a job', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.apply', $job))
        ->assertForbidden();
});

test('guest cannot apply to a job', function () {
    $job = JobPosting::factory()->create();

    $this->post(route('jobs.apply', $job))->assertRedirect(route('login'));
});

// ---------------------------------------------------------------------------
// Job Applications — withdrawing
// ---------------------------------------------------------------------------

test('student can withdraw a submitted application', function () {
    [$student, $job] = makeStudentWithJob();
    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->delete(route('jobs.apply.destroy', $job))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(JobApplication::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeFalse();
});

test('student cannot withdraw an accepted application', function () {
    [$student, $job] = makeStudentWithJob();
    JobApplication::factory()->accepted()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->delete(route('jobs.apply.destroy', $job))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(JobApplication::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeTrue();
});

test('student cannot withdraw a rejected application', function () {
    [$student, $job] = makeStudentWithJob();
    JobApplication::factory()->rejected()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->delete(route('jobs.apply.destroy', $job))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(JobApplication::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeTrue();
});

test('student cannot withdraw an application they never made', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->delete(route('jobs.apply.destroy', $job))
        ->assertNotFound();
});

test('employer cannot withdraw a student application', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->delete(route('jobs.apply.destroy', $job))
        ->assertForbidden();
});

// ---------------------------------------------------------------------------
// My Jobs page
// ---------------------------------------------------------------------------

test('student can view the my-jobs page', function () {
    $student = StudentProfile::factory()->create();

    $this->actingAs($student->user)
        ->get(route('student.my-jobs'))
        ->assertOk();
});

test('my-jobs page shows saved jobs', function () {
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create(['title' => 'Saved Internship']);
    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'saved']))
        ->assertOk()
        ->assertSee('Saved Internship');
});

test('my-jobs saved tab does not show other students saved jobs', function () {
    $student      = StudentProfile::factory()->create();
    $otherStudent = StudentProfile::factory()->create();
    $job          = JobPosting::factory()->create(['title' => 'Other Student Job']);
    SavedJob::factory()->create(['student_id' => $otherStudent->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'saved']))
        ->assertOk()
        ->assertDontSee('Other Student Job');
});

test('my-jobs page shows applied jobs', function () {
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create(['title' => 'Applied Role']);
    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'applied']))
        ->assertOk()
        ->assertSee('Applied Role');
});

test('my-jobs applied tab does not show other students applications', function () {
    $student      = StudentProfile::factory()->create();
    $otherStudent = StudentProfile::factory()->create();
    $job          = JobPosting::factory()->create(['title' => 'Other Application']);
    JobApplication::factory()->create(['student_id' => $otherStudent->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'applied']))
        ->assertOk()
        ->assertDontSee('Other Application');
});

test('employer cannot access the my-jobs page', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->get(route('student.my-jobs'))
        ->assertForbidden();
});

test('guest cannot access the my-jobs page', function () {
    $this->get(route('student.my-jobs'))->assertRedirect(route('login'));
});

// ---------------------------------------------------------------------------
// Application status visibility
// ---------------------------------------------------------------------------

test('student can see submitted status on my-jobs page', function () {
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create();
    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id, 'status' => ApplicationStatus::Submitted]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'applied']))
        ->assertOk()
        ->assertSee('Submitted');
});

test('student can see accepted status on my-jobs page', function () {
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create();
    JobApplication::factory()->accepted()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'applied']))
        ->assertOk()
        ->assertSee('Accepted');
});

test('student can see rejected status on my-jobs page', function () {
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create();
    JobApplication::factory()->rejected()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'applied']))
        ->assertOk()
        ->assertSee('Rejected');
});

// ---------------------------------------------------------------------------
// Employer — view applications
// ---------------------------------------------------------------------------

test('employer can view applications for their job', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->get(route('jobs.applications', $job))
        ->assertOk();
});

test('applications page shows applicant names', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $student  = StudentProfile::factory()->create(['first_name' => 'Alice', 'last_name' => 'Walker']);
    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($employer->user)
        ->get(route('jobs.applications', $job))
        ->assertOk()
        ->assertSee('Alice')
        ->assertSee('Walker');
});

test('applications page shows no applications message when empty', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->get(route('jobs.applications', $job))
        ->assertOk()
        ->assertSee('No applications yet');
});

test('employer cannot view applications for another employers job', function () {
    $ownerEmployer = EmployerProfile::factory()->create();
    $otherEmployer = EmployerProfile::factory()->create();
    $job           = JobPosting::factory()->create(['employer_id' => $ownerEmployer->id]);

    $this->actingAs($otherEmployer->user)
        ->get(route('jobs.applications', $job))
        ->assertForbidden();
});

test('student cannot view the applications page', function () {
    $student = StudentProfile::factory()->create();
    $job     = JobPosting::factory()->create();

    $this->actingAs($student->user)
        ->get(route('jobs.applications', $job))
        ->assertForbidden();
});

test('guest cannot view the applications page', function () {
    $job = JobPosting::factory()->create();

    $this->get(route('jobs.applications', $job))->assertRedirect(route('login'));
});

// ---------------------------------------------------------------------------
// Employer — accept / reject applications
// ---------------------------------------------------------------------------

test('employer can accept an application', function () {
    $employer    = EmployerProfile::factory()->create();
    $job         = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $application = JobApplication::factory()->create(['job_id' => $job->id]);

    $this->actingAs($employer->user)
        ->patch(route('applications.update-status', $application), ['status' => 'accepted'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($application->fresh()->status)->toBe(ApplicationStatus::Accepted);
});

test('employer can reject an application', function () {
    $employer    = EmployerProfile::factory()->create();
    $job         = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $application = JobApplication::factory()->create(['job_id' => $job->id]);

    $this->actingAs($employer->user)
        ->patch(route('applications.update-status', $application), ['status' => 'rejected'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($application->fresh()->status)->toBe(ApplicationStatus::Rejected);
});

test('employer can change accepted application to rejected', function () {
    $employer    = EmployerProfile::factory()->create();
    $job         = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $application = JobApplication::factory()->accepted()->create(['job_id' => $job->id]);

    $this->actingAs($employer->user)
        ->patch(route('applications.update-status', $application), ['status' => 'rejected'])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($application->fresh()->status)->toBe(ApplicationStatus::Rejected);
});

test('employer cannot revert application status to submitted', function () {
    $employer    = EmployerProfile::factory()->create();
    $job         = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $application = JobApplication::factory()->accepted()->create(['job_id' => $job->id]);

    $this->actingAs($employer->user)
        ->patch(route('applications.update-status', $application), ['status' => 'submitted'])
        ->assertRedirect()
        ->assertSessionHas('error');

    expect($application->fresh()->status)->toBe(ApplicationStatus::Accepted);
});

test('employer cannot update an application for another employers job', function () {
    $ownerEmployer = EmployerProfile::factory()->create();
    $otherEmployer = EmployerProfile::factory()->create();
    $job           = JobPosting::factory()->create(['employer_id' => $ownerEmployer->id]);
    $application   = JobApplication::factory()->create(['job_id' => $job->id]);

    $this->actingAs($otherEmployer->user)
        ->patch(route('applications.update-status', $application), ['status' => 'accepted'])
        ->assertForbidden();

    expect($application->fresh()->status)->toBe(ApplicationStatus::Submitted);
});

test('student cannot update application status', function () {
    $student     = StudentProfile::factory()->create();
    $application = JobApplication::factory()->create();

    $this->actingAs($student->user)
        ->patch(route('applications.update-status', $application), ['status' => 'accepted'])
        ->assertForbidden();
});

test('update status rejects invalid status values', function () {
    $employer    = EmployerProfile::factory()->create();
    $job         = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $application = JobApplication::factory()->create(['job_id' => $job->id]);

    $this->actingAs($employer->user)
        ->patch(route('applications.update-status', $application), ['status' => 'pending'])
        ->assertSessionHasErrors('status');
});

// ---------------------------------------------------------------------------
// Job show page — student UI state
// ---------------------------------------------------------------------------

test('job show page displays apply button for student who has not applied', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('Apply Now');
});

test('job show page displays withdraw button for student who has applied', function () {
    [$student, $job] = makeStudentWithJob();
    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('Withdraw Application');
});

test('job show page displays save button for student who has not saved the job', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('Save Job');
});

test('job show page displays unsave button for student who has saved the job', function () {
    [$student, $job] = makeStudentWithJob();
    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('Unsave');
});

test('job show page displays view applications link for owner employer', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('View Applications');
});

test('job show page does not display apply or save for employer', function () {
    $employer = EmployerProfile::factory()->create();
    $job      = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertDontSee('Apply Now')
        ->assertDontSee('Save Job');
});

// ---------------------------------------------------------------------------
// Fix: Employer job index shows only own postings
// ---------------------------------------------------------------------------

test('employer job index only shows their own postings', function () {
    $employer = EmployerProfile::factory()->create();
    JobPosting::factory()->create(['employer_id' => $employer->id, 'title' => 'My Posting']);
    JobPosting::factory()->create(['title' => 'Other Employer Posting']);

    $this->actingAs($employer->user)
        ->get(route('jobs.index'))
        ->assertOk()
        ->assertSee('My Posting')
        ->assertDontSee('Other Employer Posting');
});

test('employer job index does not show other employers postings', function () {
    $employer      = EmployerProfile::factory()->create();
    $otherEmployer = EmployerProfile::factory()->create();
    JobPosting::factory()->create(['employer_id' => $otherEmployer->id, 'title' => 'Rival Co Job']);

    $this->actingAs($employer->user)
        ->get(route('jobs.index'))
        ->assertOk()
        ->assertDontSee('Rival Co Job');
});

test('employer job index shows my job postings heading', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->get(route('jobs.index'))
        ->assertOk()
        ->assertSee('My Job Postings');
});

test('student job index shows all postings from all employers', function () {
    $student       = StudentProfile::factory()->create();
    $employerOne   = EmployerProfile::factory()->create();
    $employerTwo   = EmployerProfile::factory()->create();
    JobPosting::factory()->create(['employer_id' => $employerOne->id, 'title' => 'Job Alpha']);
    JobPosting::factory()->create(['employer_id' => $employerTwo->id, 'title' => 'Job Beta']);

    $this->actingAs($student->user)
        ->get(route('jobs.index'))
        ->assertOk()
        ->assertSee('Job Alpha')
        ->assertSee('Job Beta');
});

test('student job index shows job listings heading', function () {
    $student = StudentProfile::factory()->create();

    $this->actingAs($student->user)
        ->get(route('jobs.index'))
        ->assertOk()
        ->assertSee('Job Listings');
});

// ---------------------------------------------------------------------------
// Fix: Applying for a saved job removes it from saved jobs
// ---------------------------------------------------------------------------

test('applying for a saved job removes it from saved jobs', function () {
    [$student, $job] = makeStudentWithJob();
    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    $this->actingAs($student->user)->post(route('jobs.apply', $job));

    expect(SavedJob::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeFalse();
});

test('applying for a job that was not saved does not crash', function () {
    [$student, $job] = makeStudentWithJob();

    $this->actingAs($student->user)
        ->post(route('jobs.apply', $job))
        ->assertRedirect(route('jobs.show', $job))
        ->assertSessionHas('success');

    expect(JobApplication::where('student_id', $student->id)->where('job_id', $job->id)->exists())->toBeTrue();
});

test('applied job does not appear in saved jobs tab', function () {
    [$student, $job] = makeStudentWithJob();
    SavedJob::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);
    JobApplication::factory()->create(['student_id' => $student->id, 'job_id' => $job->id]);

    // Simulate the auto-unsave that store() performs
    SavedJob::where('student_id', $student->id)->where('job_id', $job->id)->delete();

    $this->actingAs($student->user)
        ->get(route('student.my-jobs', ['tab' => 'saved']))
        ->assertOk()
        ->assertDontSee($job->title);
});

// ---------------------------------------------------------------------------
// Cascade deletes
// ---------------------------------------------------------------------------

test('deleting a job posting removes its applications', function () {
    $employer    = EmployerProfile::factory()->create();
    $job         = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $application = JobApplication::factory()->create(['job_id' => $job->id]);

    $job->delete();

    expect(JobApplication::find($application->id))->toBeNull();
});

test('deleting a job posting removes its saved jobs', function () {
    $employer  = EmployerProfile::factory()->create();
    $job       = JobPosting::factory()->create(['employer_id' => $employer->id]);
    $savedJob  = SavedJob::factory()->create(['job_id' => $job->id]);

    $job->delete();

    expect(SavedJob::find($savedJob->id))->toBeNull();
});

test('deleting a student profile removes their applications', function () {
    $student     = StudentProfile::factory()->create();
    $application = JobApplication::factory()->create(['student_id' => $student->id]);

    $student->delete();

    expect(JobApplication::find($application->id))->toBeNull();
});

test('deleting a student profile removes their saved jobs', function () {
    $student  = StudentProfile::factory()->create();
    $savedJob = SavedJob::factory()->create(['student_id' => $student->id]);

    $student->delete();

    expect(SavedJob::find($savedJob->id))->toBeNull();
});
