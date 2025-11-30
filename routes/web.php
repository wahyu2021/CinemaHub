<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\LanguageController;

// Language Switcher
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Public Movie Routes
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show')->whereNumber('id');
Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/search/json', [MovieController::class, 'searchJson'])->name('movies.search.json');
Route::get('/trending', [MovieController::class, 'trending'])->name('movies.trending');

// Guest Routes (Login/Register)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->middleware('throttle:6,1');
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store'])->middleware('throttle:6,1');

    // Password Reset
    Route::get('forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Email Verification
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Watchlist Routes (Protected + Verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{movie_id}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
});