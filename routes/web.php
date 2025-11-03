<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// ======================================
// LANDING PAGE (bisa diakses siapa saja)
// ======================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ======================================
// AUTH ROUTES (bisa diakses meskipun login)
// ======================================
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ======================================
// LOGOUT (untuk semua role)
// ======================================
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======================================
// DASHBOARD ROUTES
// ======================================

// PEMBELI
Route::prefix('pembeli')
    ->middleware('auth:pembeli')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); // langsung views/dashboard.blade.php
        })->name('pembeli.dashboard');
    });

// SENIMAN
Route::prefix('seniman')
    ->middleware('auth:seniman')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('Seniman.dashboard');
        })->name('seniman.dashboard');
    });

// ADMIN
Route::prefix('admin')
    ->middleware('auth:admin')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');
    });

// ======================================
// REDIRECT AFTER LOGIN (opsional)
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
