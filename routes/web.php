<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Dashboard Route - Protected by Auth middleware
Route::get('/dashboard', function () {
    return view('layouts.app');
})->middleware('auth')->name('dashboard');

// Catch-all Route for SPA
Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '^(?!api|login|register).*')->middleware('auth');
