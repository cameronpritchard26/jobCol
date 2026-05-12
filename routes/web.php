<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EducationEntryController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/find-someone', [HomeController::class, 'underConstruction']);
    Route::get('/learn-skill', [HomeController::class, 'underConstruction']);
    Route::get('/messages', [HomeController::class, 'underConstruction']);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('account_type:student')->group(function () {
        Route::get('/profile/education/create', [EducationEntryController::class, 'create'])->name('education.create');
        Route::post('/profile/education', [EducationEntryController::class, 'store'])->name('education.store');
        Route::get('/profile/education/{entry}/edit', [EducationEntryController::class, 'edit'])->name('education.edit');
        Route::put('/profile/education/{entry}', [EducationEntryController::class, 'update'])->name('education.update');
        Route::delete('/profile/education/{entry}', [EducationEntryController::class, 'destroy'])->name('education.destroy');
    });
});
