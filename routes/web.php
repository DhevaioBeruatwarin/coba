<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page (bisa diakses semua orang)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication routes (hanya untuk guest/belum login)
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', function () {
        return view('register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout (hanya untuk yang sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Pembeli (harus login sebagai pembeli)
Route::middleware(['auth:pembeli'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('pembeli.dashboard');

    // Tambahkan route pembeli lainnya di sini
});

// Dashboard Seniman (harus login sebagai seniman)
Route::middleware(['auth:seniman'])->group(function () {
    Route::get('/seniman/dashboard', function () {
        return view('seniman-dashboard'); // Sesuaikan dengan nama view Anda
    })->name('seniman.dashboard');

    // Tambahkan route seniman lainnya di sini
});

// Dashboard Admin (harus login sebagai admin)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin-dashboard'); // Sesuaikan dengan nama view Anda
    })->name('admin.dashboard');

    // Tambahkan route admin lainnya di sini
});

// Route testing (opsional, bisa dihapus)
Route::get('/fsfsfsfsf', function () {
    return view('welcome');
});