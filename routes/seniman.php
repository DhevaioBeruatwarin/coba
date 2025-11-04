<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryaSeniController;
use App\Http\Controllers\DashboardSenimanController;

// SENIMAN ROUTES
Route::middleware(['auth:seniman'])->group(function () {
    Route::get('/Seniman/dashboard', [DashboardSenimanController::class, 'index'])->name('seniman.dashboard');
    Route::get('/Seniman/profil/edit', [DashboardSenimanController::class, 'editProfil'])->name('seniman.profil.edit');
    Route::put('/Seniman/profil/update', [DashboardSenimanController::class, 'updateProfil'])->name('seniman.profil.update');

    Route::get('/Seniman/karya/create', [DashboardSenimanController::class, 'createKarya'])->name('seniman.karya.create');
    Route::post('/Seniman/karya/store', [DashboardSenimanController::class, 'storeKarya'])->name('seniman.karya.store');
    Route::get('/Seniman/karya/{id}/edit', [DashboardSenimanController::class, 'editKarya'])->name('seniman.karya.edit');
    Route::put('/Seniman/karya/{id}/update', [DashboardSenimanController::class, 'updateKarya'])->name('seniman.karya.update');
    Route::delete('/Seniman/karya/{id}/delete', [DashboardSenimanController::class, 'destroyKarya'])->name('seniman.karya.destroy');
});

Route::middleware(['auth:seniman'])->group(function () {
    Route::get('/seniman/dashboard', [KaryaSeniController::class, 'index'])->name('seniman.dashboard');
    Route::get('/seniman/upload', [KaryaSeniController::class, 'create'])->name('seniman.upload');
    Route::post('/seniman/upload', [KaryaSeniController::class, 'store'])->name('seniman.store');
    Route::get('/seniman/karya/{id}', [KaryaSeniController::class, 'show'])->name('seniman.show');
    Route::delete('/seniman/karya/{id}', [KaryaSeniController::class, 'destroy'])->name('seniman.destroy');


});

