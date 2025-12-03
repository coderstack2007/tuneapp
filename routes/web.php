<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SpotifyController;
Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/spotify', [SpotifyController::class, 'index'])->name('dashboard.spotify');
        Route::get('/spotify/connect', [SpotifyController::class, 'connect'])->name('dashboard.spotify.connect');
        Route::get('/spotify/callback', [SpotifyController::class, 'callback'])->name('dashboard.spotify.callback');
    });
});
require __DIR__.'/auth.php';
