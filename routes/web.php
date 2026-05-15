<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EducationEntryController;
use App\Http\Controllers\ExperienceEntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\SavedJobController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/search-job', [HomeController::class, 'underConstruction']);
    Route::get('/network', [NetworkController::class, 'index'])->name('network.index');
    Route::get('/profile/student/{studentProfile}', [NetworkController::class, 'showStudent'])->name('profile.student.public');
    Route::get('/profile/employer/{employerProfile}', [NetworkController::class, 'showEmployer'])->name('profile.employer.public');
    Route::get('/learn-skill', [HomeController::class, 'underConstruction']);
    Route::get('/messages', [HomeController::class, 'underConstruction']);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/profile/picture', [ProfilePictureController::class, 'update'])->name('profile.picture.update');
    Route::get('/profile/picture/status', [ProfilePictureController::class, 'status'])->name('profile.picture.status');
    Route::delete('/profile/picture', [ProfilePictureController::class, 'destroy'])->name('profile.picture.destroy');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

    Route::middleware('account_type:employer')->group(function () {
        Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{jobPosting}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{jobPosting}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{jobPosting}', [JobController::class, 'destroy'])->name('jobs.destroy');
        Route::get('/jobs/{jobPosting}/applications', [JobApplicationController::class, 'indexForJob'])->name('jobs.applications');
        Route::patch('/applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('applications.update-status');
    });

    Route::get('/jobs/{jobPosting}', [JobController::class, 'show'])->name('jobs.show');

    Route::middleware('account_type:student')->group(function () {
        Route::get('/profile/education/create', [EducationEntryController::class, 'create'])->name('education.create');
        Route::post('/profile/education', [EducationEntryController::class, 'store'])->name('education.store');
        Route::get('/profile/education/{entry}/edit', [EducationEntryController::class, 'edit'])->name('education.edit');
        Route::put('/profile/education/{entry}', [EducationEntryController::class, 'update'])->name('education.update');
        Route::delete('/profile/education/{entry}', [EducationEntryController::class, 'destroy'])->name('education.destroy');

        Route::get('/profile/experience/create', [ExperienceEntryController::class, 'create'])->name('experience.create');
        Route::post('/profile/experience', [ExperienceEntryController::class, 'store'])->name('experience.store');
        Route::get('/profile/experience/{entry}/edit', [ExperienceEntryController::class, 'edit'])->name('experience.edit');
        Route::put('/profile/experience/{entry}', [ExperienceEntryController::class, 'update'])->name('experience.update');
        Route::delete('/profile/experience/{entry}', [ExperienceEntryController::class, 'destroy'])->name('experience.destroy');

        Route::post('/jobs/{jobPosting}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');
        Route::delete('/jobs/{jobPosting}/apply', [JobApplicationController::class, 'destroy'])->name('jobs.apply.destroy');
        Route::post('/jobs/{jobPosting}/save', [SavedJobController::class, 'store'])->name('jobs.save');
        Route::delete('/jobs/{jobPosting}/unsave', [SavedJobController::class, 'destroy'])->name('jobs.unsave');
        Route::get('/my-jobs', [SavedJobController::class, 'index'])->name('student.my-jobs');

        Route::post('/connections/{studentProfile}', [ConnectionController::class, 'store'])->name('connections.store');
        Route::put('/connections/{connection}/accept', [ConnectionController::class, 'accept'])->name('connections.accept');
        Route::put('/connections/{connection}/reject', [ConnectionController::class, 'reject'])->name('connections.reject');
        Route::delete('/connections/{connection}', [ConnectionController::class, 'destroy'])->name('connections.destroy');
    });
});
