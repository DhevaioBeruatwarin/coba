<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaSeni;
use App\Models\Seniman;
use Illuminate\Support\Facades\Auth;

class KaryaSeniController extends Controller
{
    // Menampilkan detail karya untuk pembeli/umum
    public function show($kode_seni)
    {
        // Ambil karya berdasarkan kode_seni dengan relasi seniman dan rating (jika ada)
        $karya = KaryaSeni::with(['seniman', 'reviews'])->where('kode_seni', $kode_seni)->firstOrFail();

        // Hitung rata-rata rating, kalau belum ada maka null
        $averageRating = null;
        if ($karya->reviews && $karya->reviews->count() > 0) {
            $averageRating = round($karya->reviews->avg('nilai'), 1);
        }

        // Ambil karya lain dari seniman yang sama (untuk rekomendasi)
        $karyaSeniman = KaryaSeni::where('id_seniman', $karya->id_seniman)
            ->where('kode_seni', '!=', $kode_seni)
            ->limit(4)
            ->get();

        // Ambil karya sejenis/random untuk rekomendasi lainnya
        $karyaLainnya = KaryaSeni::where('kode_seni', '!=', $kode_seni)
            ->latest()
            ->limit(8)
            ->get();

        // Kirim semua data ke view
        return view('Seniman.detail_karya', compact('karya', 'karyaSeniman', 'karyaLainnya', 'averageRating'));
    }

    // Menampilkan dashboard seniman
    public function index()
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('id_seniman', $seniman->id_seniman)->get();

        return view('Seniman.dashboard', compact('karya', 'seniman'));
    }

    // Menyimpan karya baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $seniman = Auth::guard('seniman')->user();
        $path = $request->file('gambar')->store('karya_seni', 'public');

        $kodeSeni = 'KS' . time() . rand(100, 999);

        KaryaSeni::create([
            'kode_seni' => $kodeSeni,
            'id_seniman' => $seniman->id_seniman,
            'nama_karya' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => basename($path),
        ]);

        return redirect()->route('seniman.dashboard')->with('success', 'Karya berhasil diupload!');
    }

    // Menghapus karya
    public function destroy($kode_seni)
    {
        $karya = KaryaSeni::where('kode_seni', $kode_seni)->firstOrFail();
        $karya->delete();

        return redirect()->route('seniman.dashboard')->with('success', 'Karya berhasil dihapus!');
    }
}
