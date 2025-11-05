<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardPembeliController;
use App\Http\Controllers\DashboardSenimanController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\SenimanController;

// ======================================
// LANDING PAGE
// ======================================
Route::get('/', function () {
    return view('landing');
})->name('landing');


// ======================================
// AUTH ROUTES (LOGIN, REGISTER, LOGOUT)
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

        // Dashboard Pembeli
        Route::get('/dashboard', [DashboardPembeliController::class, 'index'])->name('pembeli.dashboard');

        // Profil Pembeli
        Route::get('/profil', [PembeliController::class, 'profil'])->name('pembeli.profil');
    });


// ======================================
// DASHBOARD SENIMAN
// ======================================
Route::prefix('seniman') // ✅ huruf kecil semua
    ->middleware('auth:seniman') // ✅ huruf kecil semua
    ->group(function () {

        // Dashboard Seniman
        Route::get('/dashboard', [DashboardSenimanController::class, 'index'])->name('seniman.dashboard');

        // Profil Seniman
        Route::get('/profile', [SenimanController::class, 'profile'])->name('seniman.profile');

        // Edit Profil Seniman
        Route::get('/profile/edit', [SenimanController::class, 'edit'])->name('seniman.profile.edit');

        // Update Profil Seniman
        Route::post('/profile/update', [SenimanController::class, 'update'])->name('seniman.profile.update');

        // Detail Karya
        Route::get('/karya/{id}', function ($id) {
            return view('Seniman.detail_karya', ['id' => $id]);
        })->name('seniman.karya.detail');
    });


// ======================================
// DASHBOARD ADMIN
// ======================================
Route::prefix('admin')
    ->middleware('auth:admin')
    ->group(function () {

        // Dashboard Admin
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
    } elseif (Auth::guard('seniman')->check()) { // ✅ lowercase
        return redirect()->route('seniman.dashboard'); // ✅ lowercase
    } elseif (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('landing');
    }
})->name('redirect.after.login');
