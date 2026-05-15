<?php

use App\Enums\JobType;
use App\Enums\SalaryType;
use App\Models\EmployerProfile;
use App\Models\JobPosting;
use App\Models\StudentProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Authentication
// ---------------------------------------------------------------------------

test('guests are redirected from job index', function () {
    $this->get(route('jobs.index'))->assertRedirect(route('login'));
});

test('guests are redirected from job show', function () {
    $job = JobPosting::factory()->create();
    $this->get(route('jobs.show', $job))->assertRedirect(route('login'));
});

test('guests cannot access job create form', function () {
    $this->get(route('jobs.create'))->assertRedirect(route('login'));
});

// ---------------------------------------------------------------------------
// Browsing (all authenticated users)
// ---------------------------------------------------------------------------

test('authenticated student can view job index', function () {
    $student = StudentProfile::factory()->create();
    JobPosting::factory()->count(3)->create();

    $this->actingAs($student->user)
        ->get(route('jobs.index'))
        ->assertOk();
});

test('authenticated employer can view job index', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->get(route('jobs.index'))
        ->assertOk();
});

test('job index displays posted jobs', function () {
    $employer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create([
        'employer_id' => $employer->id,
        'title' => 'Senior Laravel Developer',
    ]);

    $this->actingAs($employer->user)
        ->get(route('jobs.index'))
        ->assertSee('Senior Laravel Developer');
});

test('authenticated user can view a job posting', function () {
    $student = StudentProfile::factory()->create();
    $job = JobPosting::factory()->create(['title' => 'Data Analyst']);

    $this->actingAs($student->user)
        ->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('Data Analyst');
});

// ---------------------------------------------------------------------------
// Employer — create
// ---------------------------------------------------------------------------

test('employer can view the create job form', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->get(route('jobs.create'))
        ->assertOk();
});

test('employer can create a job posting', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'       => 'Backend Engineer',
            'description' => 'Build and maintain APIs.',
            'location'    => 'Austin, TX',
            'job_type'    => JobType::FullTime->value,
            'salary'      => 120000,
            'salary_type' => SalaryType::Annually->value,
            'deadline'    => now()->addMonths(2)->format('Y-m-d'),
        ])
        ->assertRedirect(route('jobs.index'))
        ->assertSessionHas('success');

    expect(JobPosting::where('title', 'Backend Engineer')->exists())->toBeTrue();
});

test('store validation requires salary', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'       => 'Missing Salary Job',
            'description' => 'No salary provided.',
            'location'    => 'Remote',
            'job_type'    => JobType::Internship->value,
            'salary_type' => SalaryType::Hourly->value,
            'deadline'    => now()->addMonth()->format('Y-m-d'),
        ])
        ->assertSessionHasErrors('salary');
});

test('store validation requires salary type', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'       => 'Missing Pay Period Job',
            'description' => 'No salary type provided.',
            'location'    => 'Remote',
            'job_type'    => JobType::Internship->value,
            'salary'      => 20,
            'deadline'    => now()->addMonth()->format('Y-m-d'),
        ])
        ->assertSessionHasErrors('salary_type');
});

test('store validation requires deadline', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'       => 'No Deadline Job',
            'description' => 'No deadline provided.',
            'location'    => 'Remote',
            'job_type'    => JobType::Internship->value,
            'salary'      => 20,
            'salary_type' => SalaryType::Hourly->value,
        ])
        ->assertSessionHasErrors('deadline');
});

test('created job is linked to the posting employer', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)->post(route('jobs.store'), [
        'title'       => 'QA Engineer',
        'description' => 'Test all the things.',
        'location'    => 'NYC',
        'job_type'    => JobType::Contract->value,
        'salary'      => 90000,
        'salary_type' => SalaryType::Annually->value,
        'deadline'    => now()->addMonths(3)->format('Y-m-d'),
    ]);

    $job = JobPosting::where('title', 'QA Engineer')->first();
    expect($job->employer_id)->toBe($employer->id);
});

// ---------------------------------------------------------------------------
// Employer — edit / update
// ---------------------------------------------------------------------------

test('employer can view edit form for their own job', function () {
    $employer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->get(route('jobs.edit', $job))
        ->assertOk();
});

test('employer can update their own job posting', function () {
    $employer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->put(route('jobs.update', $job), [
            'title'       => 'Updated Title',
            'description' => 'Updated description.',
            'location'    => 'Chicago, IL',
            'job_type'    => JobType::PartTime->value,
            'salary'      => 45000,
            'salary_type' => SalaryType::Annually->value,
            'deadline'    => now()->addMonths(2)->format('Y-m-d'),
        ])
        ->assertRedirect(route('jobs.show', $job))
        ->assertSessionHas('success');

    expect($job->fresh()->title)->toBe('Updated Title');
});

// ---------------------------------------------------------------------------
// Employer — delete
// ---------------------------------------------------------------------------

test('employer can delete their own job posting', function () {
    $employer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create(['employer_id' => $employer->id]);

    $this->actingAs($employer->user)
        ->delete(route('jobs.destroy', $job))
        ->assertRedirect(route('jobs.index'))
        ->assertSessionHas('success');

    expect(JobPosting::find($job->id))->toBeNull();
});

// ---------------------------------------------------------------------------
// Employer — cross-employer authorization
// ---------------------------------------------------------------------------

test('employer cannot view edit form for another employers job', function () {
    $ownerEmployer = EmployerProfile::factory()->create();
    $otherEmployer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create(['employer_id' => $ownerEmployer->id]);

    $this->actingAs($otherEmployer->user)
        ->get(route('jobs.edit', $job))
        ->assertForbidden();
});

test('employer cannot update another employers job posting', function () {
    $ownerEmployer = EmployerProfile::factory()->create();
    $otherEmployer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create(['employer_id' => $ownerEmployer->id]);

    $this->actingAs($otherEmployer->user)
        ->put(route('jobs.update', $job), [
            'title'       => 'Hijacked Title',
            'description' => 'Hijacked description.',
            'location'    => 'Nowhere',
            'job_type'    => JobType::FullTime->value,
        ])
        ->assertForbidden();

    expect($job->fresh()->title)->not->toBe('Hijacked Title');
});

test('employer cannot delete another employers job posting', function () {
    $ownerEmployer = EmployerProfile::factory()->create();
    $otherEmployer = EmployerProfile::factory()->create();
    $job = JobPosting::factory()->create(['employer_id' => $ownerEmployer->id]);

    $this->actingAs($otherEmployer->user)
        ->delete(route('jobs.destroy', $job))
        ->assertForbidden();

    expect(JobPosting::find($job->id))->not->toBeNull();
});

// ---------------------------------------------------------------------------
// Student authorization
// ---------------------------------------------------------------------------

test('student cannot access job create form', function () {
    $student = StudentProfile::factory()->create();

    $this->actingAs($student->user)
        ->get(route('jobs.create'))
        ->assertForbidden();
});

test('student cannot post a job', function () {
    $student = StudentProfile::factory()->create();

    $this->actingAs($student->user)
        ->post(route('jobs.store'), [
            'title'       => 'Sneaky Job',
            'description' => 'Should not be created.',
            'location'    => 'Remote',
            'job_type'    => JobType::FullTime->value,
        ])
        ->assertForbidden();

    expect(JobPosting::count())->toBe(0);
});

test('student cannot edit a job posting', function () {
    $student = StudentProfile::factory()->create();
    $job = JobPosting::factory()->create();

    $this->actingAs($student->user)
        ->get(route('jobs.edit', $job))
        ->assertForbidden();
});

test('student cannot delete a job posting', function () {
    $student = StudentProfile::factory()->create();
    $job = JobPosting::factory()->create();

    $this->actingAs($student->user)
        ->delete(route('jobs.destroy', $job))
        ->assertForbidden();

    expect(JobPosting::find($job->id))->not->toBeNull();
});

// ---------------------------------------------------------------------------
// Validation
// ---------------------------------------------------------------------------

test('store validation requires title', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'description' => 'A job without a title.',
            'location'    => 'Remote',
            'job_type'    => JobType::FullTime->value,
        ])
        ->assertSessionHasErrors('title');
});

test('store validation requires description', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'    => 'No Description Job',
            'location' => 'Remote',
            'job_type' => JobType::FullTime->value,
        ])
        ->assertSessionHasErrors('description');
});

test('store validation rejects invalid job type', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'       => 'Bad Type Job',
            'description' => 'Has an invalid type.',
            'location'    => 'Remote',
            'job_type'    => 'freelance',
        ])
        ->assertSessionHasErrors('job_type');
});


test('store validation rejects deadline in the past', function () {
    $employer = EmployerProfile::factory()->create();

    $this->actingAs($employer->user)
        ->post(route('jobs.store'), [
            'title'       => 'Expired Job',
            'description' => 'Deadline already passed.',
            'location'    => 'Remote',
            'job_type'    => JobType::FullTime->value,
            'deadline'    => now()->subDay()->format('Y-m-d'),
        ])
        ->assertSessionHasErrors('deadline');
});
