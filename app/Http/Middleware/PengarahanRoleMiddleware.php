<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout route (authenticated only)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pembeli routes
Route::middleware(['auth:pembeli'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pembeli.dashboard');
    })->name('pembeli.dashboard');

    // Tambahkan route pembeli lainnya di sini
});

// Seniman routes
Route::middleware(['auth:seniman'])->group(function () {
    Route::get('/seniman/dashboard', function () {
        return view('seniman.dashboard');
    })->name('seniman.dashboard');

    // Tambahkan route seniman lainnya di sini
});

// Admin routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Tambahkan route admin lainnya di sini
});