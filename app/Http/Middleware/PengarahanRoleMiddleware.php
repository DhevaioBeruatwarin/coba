<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page (bisa diakses siapa saja)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication routes (hanya untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout (untuk user yang sudah login di salah satu guard)
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('web'); // cukup 'web', karena kita handle logout semua guard di controller

// ===================== PEMBELI =====================
Route::prefix('pembeli')
    ->middleware('auth:pembeli')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('pembeli.dashboard');
    });

// ===================== SENIMAN =====================
Route::prefix('seniman')
    ->middleware('auth:seniman')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('Seniman.dashboard');
        })->name('seniman.dashboard');
    });

// ===================== ADMIN =====================
Route::prefix('admin')
    ->middleware('auth:admin')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');
    });
