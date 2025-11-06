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
        Route::get('/profil/edit', [PembeliController::class, 'edit'])->name('pembeli.profil.edit');
        Route::put('/profil/update', [PembeliController::class, 'update'])->name('pembeli.profil.update');

        // Upload foto - GANTI JADI POST
        Route::post('/profil/foto', [PembeliController::class, 'updateFoto'])->name('pembeli.profil.update_foto');
    });


// ======================================
// DASHBOARD SENIMAN
// ======================================
Route::prefix('seniman')
    ->middleware('auth:seniman')
    ->group(function () {

        // Dashboard Seniman
        Route::get('/dashboard', [DashboardSenimanController::class, 'index'])->name('seniman.dashboard');

        // Profil Seniman
        Route::get('/profil', [SenimanController::class, 'profile'])->name('seniman.profil');
        Route::get('/profil/edit', [SenimanController::class, 'edit'])->name('seniman.edit.profil');
        Route::put('/profil/update', [SenimanController::class, 'update'])->name('seniman.profil.update');

        // Upload foto - GANTI JADI POST
        Route::post('/profil/foto', [SenimanController::class, 'updateFoto'])->name('seniman.profil.foto.update');

        // CRUD Karya Seniman
        Route::get('/karya/upload', [DashboardSenimanController::class, 'createKarya'])->name('seniman.karya.upload');
        Route::post('/karya/store', [DashboardSenimanController::class, 'storeKarya'])->name('seniman.karya.store');
        Route::get('/karya/edit/{kode_seni}', [DashboardSenimanController::class, 'editKarya'])->name('seniman.karya.edit');
        Route::put('/karya/update/{kode_seni}', [DashboardSenimanController::class, 'updateKarya'])->name('seniman.karya.update');
        Route::delete('/karya/delete/{kode_seni}', [DashboardSenimanController::class, 'destroyKarya'])->name('seniman.karya.delete');

        // Detail Karya
        Route::get('/karya/{id}', function ($id) {
            return view('Seniman.detail_karya', ['id' => $id]);
        })->name('seniman.karya.detail');
    });

//logout

Route::get('/seniman/logout', function () {
    Auth::guard('seniman')->logout();
    return redirect()->route('login')->with('success', 'Berhasil logout!');
})->name('seniman.logout');


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
    } elseif (Auth::guard('seniman')->check()) {
        return redirect()->route('seniman.dashboard');
    } elseif (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('landing');
    }
})->name('redirect.after.login');

