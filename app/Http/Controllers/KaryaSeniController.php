<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaSeni;
use Illuminate\Support\Facades\Auth;

class KaryaSeniController extends Controller
{
    // Menampilkan dashboard seniman (daftar karya milik seniman login)
    public function index()
    {
        $seniman = Auth::guard('seniman')->user();

        $karya = KaryaSeni::where('seniman_id', $seniman->id)->get();

        return view('Seniman.dashboard', compact('karya', 'seniman'));
    }

    public function create()
    {
        return view('Seniman.upload_karya');
    }

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

        KaryaSeni::create([
            'seniman_id' => $seniman->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $path,
        ]);

        return redirect()->route('seniman.dashboard')->with('success', 'Karya berhasil diupload!');
    }

    public function show($id)
    {
        $karya = KaryaSeni::findOrFail($id);
        return view('Seniman.detail_karya', compact('karya'));
    }

    public function destroy($id)
    {
        $karya = KaryaSeni::findOrFail($id);
        $karya->delete();

        return redirect()->route('seniman.dashboard')->with('success', 'Karya berhasil dihapus!');
    }
}
