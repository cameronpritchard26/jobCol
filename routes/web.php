<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentProfileController;
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

    Route::middleware('account_type:student')->group(function () {
        Route::get('/profile', [StudentProfileController::class, 'show'])->name('student.profile.show');
        Route::get('/profile/create', [StudentProfileController::class, 'create'])->name('student.profile.create');
        Route::post('/profile', [StudentProfileController::class, 'store'])->name('student.profile.store');
        Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
        Route::put('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
    });
});
