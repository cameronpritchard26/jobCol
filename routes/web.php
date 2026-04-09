<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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
});
