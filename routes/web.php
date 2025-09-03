<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    //Movie Details Route
    Route::get('movie/{id}', [DashboardController::class, 'showMovie'])->name('movies.show');

    //TV Show Details Route
    Route::get('tv/{id}', [DashboardController::class, 'showTv'])->name('tv.show');

    //Actor Details Route
    Route::get('actor/{id}', [DashboardController::class, 'showActor'])->name('actor.show');

    //Genre Details Route
    Route::get('/genre/{id}', [DashboardController::class, 'showGenre'])->name('genre.show');
});

Route::middleware(['auth', 'web'])->group(function () {
    Route::post('favorites/toggle/{type}/{itemId}', [FavouritesController::class, 'toggle'])
        ->name('favorites.toggle');
});

require __DIR__.'/auth.php';
