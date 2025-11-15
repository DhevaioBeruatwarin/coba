<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardPembeliController;
use App\Http\Controllers\DashboardSenimanController;
use App\Http\Controllers\KaryaSeniController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\SenimanController;
use App\Http\Controllers\keranjangController;

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
        Route::get('/dashboard/pembeli/search', [KaryaSeniController::class, 'search'])->name('dashboard.seniman.search');

        // Profil Pembeli
        Route::get('/profil', [PembeliController::class, 'profil'])->name('pembeli.profil');
        Route::get('/profil/edit', [PembeliController::class, 'edit'])->name('pembeli.profil.edit');
        Route::put('/profil/update', [PembeliController::class, 'update'])->name('pembeli.profil.update');
        Route::post('/profil/foto', [PembeliController::class, 'updateFoto'])->name('pembeli.profil.update_foto');

        // Logout Pembeli
        Route::get('/logout', function () {
            Auth::guard('pembeli')->logout();
            return redirect()->route('login')->with('success', 'Berhasil logout!');
        })->name('pembeli.logout');

        // Keranjang Routes
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
        Route::post('/keranjang/tambah/{kode_seni}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
        Route::get('/keranjang/count', [KeranjangController::class, 'count'])->name('keranjang.count');
        Route::post('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
        Route::post('/keranjang/hapus-bulk', [KeranjangController::class, 'hapusBulk'])->name('keranjang.hapusBulk');

        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
        // Checkout
        Route::post('/keranjang/checkout', [KeranjangController::class, 'prepareCheckout'])
            ->name('keranjang.checkout');

        Route::get('/checkout', [KeranjangController::class, 'checkoutPreview'])
            ->name('pembeli.checkout.preview');

        Route::post('/checkout/bayar', [KeranjangController::class, 'bayar'])
            ->name('pembeli.checkout.bayar');

    });

// ======================================
// DASHBOARD SENIMAN
// ======================================
Route::prefix('seniman')
    ->middleware('auth:seniman')
    ->group(function () {

        // Dashboard Seniman
        Route::get('/dashboard', [DashboardSenimanController::class, 'index'])->name('seniman.dashboard');
        Route::get('/dashboard/pembeli/search', [KaryaSeniController::class, 'search'])->name('dashboard.pembeli.search');
        // Profil Seniman
        Route::get('/profil', [SenimanController::class, 'profile'])->name('seniman.profil');
        Route::get('/profil/edit', [SenimanController::class, 'edit'])->name('seniman.edit.profil');
        Route::put('/profil/update', [SenimanController::class, 'update'])->name('seniman.profil.update');
        Route::post('/profil/foto', [SenimanController::class, 'updateFoto'])->name('seniman.profil.foto.update');

        // CRUD Karya Seniman
        Route::get('/karya', [SenimanController::class, 'karyaSaya'])->name('seniman.karya.index');
        Route::get('/karya/upload', [DashboardSenimanController::class, 'createKarya'])->name('seniman.karya.upload');
        Route::post('/karya/store', [DashboardSenimanController::class, 'storeKarya'])->name('seniman.karya.store');
        Route::get('/karya/edit/{kode_seni}', [DashboardSenimanController::class, 'editKarya'])->name('seniman.karya.edit');
        Route::put('/karya/update/{kode_seni}', [DashboardSenimanController::class, 'updateKarya'])->name('seniman.karya.update');
        Route::delete('/karya/delete/{kode_seni}', [DashboardSenimanController::class, 'destroyKarya'])->name('seniman.karya.delete');

        // Logout Seniman
        Route::get('/logout', function () {
            Auth::guard('seniman')->logout();
            return redirect()->route('login')->with('success', 'Berhasil logout!');
        })->name('seniman.logout');
    });

// ======================================
// DETAIL KARYA (Publik & Pembeli bisa akses)
// ======================================
Route::get('/karya/{kode_seni}', [KaryaSeniController::class, 'show'])->name('karya.detail');



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
