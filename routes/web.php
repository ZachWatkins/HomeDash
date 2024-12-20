<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'now' => now(),
        'events' => \App\Models\Event::where('show_on_countdown', true)->get(),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('loudness', [App\Http\Controllers\LoudnessController::class, 'show'])->name('loudness.show');
Route::get('events/countdowns', [App\Http\Controllers\EventController::class, 'countdowns'])->name('events.countdowns');
Route::get('events/trips', [App\Http\Controllers\EventController::class, 'trips'])->name('events.trips');

Route::resource('events', App\Http\Controllers\EventController::class)->middleware('event.timezone');
