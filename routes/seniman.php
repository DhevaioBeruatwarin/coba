<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryaSeniController;
use App\Http\Controllers\DashboardSenimanController;

// SENIMAN ROUTES
Route::middleware(['auth:Seniman'])->group(function () {
    Route::get('/Seniman/dashboard', [DashboardSenimanController::class, 'index'])->name('Seniman.dashboard');
    Route::get('/Seniman/profil/edit', [DashboardSenimanController::class, 'editProfil'])->name('Seniman.profil.edit');
    Route::put('/Seniman/profil/update', [DashboardSenimanController::class, 'updateProfil'])->name('Seniman.profil.update');

    Route::get('/Seniman/karya/create', [DashboardSenimanController::class, 'createKarya'])->name('Seniman.karya.create');
    Route::post('/Seniman/karya/store', [DashboardSenimanController::class, 'storeKarya'])->name('Seniman.karya.store');
    Route::get('/Seniman/karya/{id}/edit', [DashboardSenimanController::class, 'editKarya'])->name('Seniman.karya.edit');
    Route::put('/Seniman/karya/{id}/update', [DashboardSenimanController::class, 'updateKarya'])->name('Seniman.karya.update');
    Route::delete('/Seniman/karya/{id}/delete', [DashboardSenimanController::class, 'destroyKarya'])->name('Seniman.karya.destroy');
});

Route::middleware(['auth:Seniman'])->group(function () {
    Route::get('/Seniman/dashboard', [KaryaSeniController::class, 'index'])->name('Seniman.dashboard');
    Route::get('/Seniman/upload', [KaryaSeniController::class, 'create'])->name('Seniman.upload');
    Route::post('/Seniman/upload', [KaryaSeniController::class, 'store'])->name('Seniman.store');
    Route::get('/Seniman/karya/{id}', [KaryaSeniController::class, 'show'])->name('Seniman.show');
    Route::delete('/Seniman/karya/{id}', [KaryaSeniController::class, 'destroy'])->name('Seniman.destroy');


});

