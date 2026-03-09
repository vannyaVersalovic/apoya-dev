<?php

use App\Http\Controllers\CreatorPageController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// ✅ Ruta raíz → redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas públicas
Route::get('/@{slug}', [PublicProfileController::class, 'show'])
    ->name('public.profile');

Route::post('/@{slug}/support', [SupportController::class, 'store'])
    ->name('public.support.store');

// Rutas privadas
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [CreatorPageController::class, 'index'])
        ->name('dashboard');

    Route::post('/creator-page', [CreatorPageController::class, 'store'])
        ->name('creator-page.store');

    Route::put('/creator-page', [CreatorPageController::class, 'update'])
        ->name('creator-page.update');

    Route::post('/links', [LinkController::class, 'store'])
        ->name('links.store');

    Route::put('/links/{link}', [LinkController::class, 'update'])
        ->name('links.update');

    Route::delete('/links/{link}', [LinkController::class, 'destroy'])
        ->name('links.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
