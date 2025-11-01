<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaSeni;

class DashboardPembeliController extends Controller
{
    public function __construct()
    {
        // hanya pembeli yang bisa akses
        $this->middleware('auth:pembeli');
    }

    public function index()
    {
        // ambil semua karya seni dari database
        $karyaSeni = KaryaSeni::latest()->get();

        // kalau kosong kasih pesan
        $message = $karyaSeni->isEmpty() ? 'Belum ada karya seni yang tersedia.' : null;

        // kirim ke view
        return view('dashboard', compact('karyaSeni', 'message'));
    }
}
