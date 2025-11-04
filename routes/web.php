<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardPembeliController;
use App\Http\Controllers\KaryaSeniController;

// ======================================
// LANDING PAGE
// ======================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ======================================
// AUTH ROUTES
// ======================================
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======================================
// DASHBOARD PEMBELI
// ======================================
Route::prefix('pembeli')
    ->middleware('auth:pembeli')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); // langsung views/dashboard.blade.php
        })->name('pembeli.dashboard');

        // Profil pembeli
        Route::get('/profil', [\App\Http\Controllers\PembeliController::class, 'profil'])->name('pembeli.profil');
    });

// ======================================
// DASHBOARD SENIMAN
// ======================================
Route::prefix('seniman')
    ->middleware('auth:seniman')
    ->group(function () {
        Route::get('/dashboard', [KaryaSeniController::class, 'index'])->name('seniman.dashboard');
        Route::get('/upload', [KaryaSeniController::class, 'create'])->name('seniman.upload');
        Route::post('/upload', [KaryaSeniController::class, 'store'])->name('seniman.store');
        Route::get('/karya/{id}', [KaryaSeniController::class, 'show'])->name('seniman.karya.detail');
        Route::delete('/karya/{id}', [KaryaSeniController::class, 'destroy'])->name('seniman.karya.delete');
    });

// ======================================
// DASHBOARD ADMIN
// ======================================
Route::prefix('admin')
    ->middleware('auth:admin')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');
    });

// ======================================
// REDIRECT AFTER LOGIN
// ======================================
Route::get('/redirect-after-login', function () {
    if (Auth::guard('pembeli')->check()) {
        return redirect()->route('pembeli.dashboard');
    } elseif (Auth::guard('seniman')->check()) {
        return redirect()->route('seniman.dashboard');
    } elseif (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('landing');
    }
})->name('redirect.after.login');


// ======================================
// akses ke profile pembeli
// ======================================
Route::get('/profile', function () {
    return view('profile');
});

