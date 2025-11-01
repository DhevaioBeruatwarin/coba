<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seniman;
use App\Models\KaryaSeni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardSenimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seniman'); // pastikan guard seniman
    }

    // Dashboard utama
    public function index()
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('seniman_id', $seniman->id)->latest()->get();

        return view('Seniman.dashboard', compact('seniman', 'karya'));
    }

    // Edit profil seniman
    public function editProfil()
    {
        $seniman = Auth::guard('seniman')->user();
        return view('Seniman.edit_profil', compact('seniman'));
    }

    public function updateProfil(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $seniman->nama = $request->nama;
        $seniman->bio = $request->bio;

        if ($request->hasFile('foto')) {
            if ($seniman->foto) {
                Storage::delete('public/foto_seniman/' . $seniman->foto);
            }
            $fotoName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/foto_seniman', $fotoName);
            $seniman->foto = $fotoName;
        }

        $seniman->save();

        return redirect()->route('seniman.dashboard')->with('success', 'Profil berhasil diperbarui!');
    }

    // CRUD karya seni
    public function createKarya()
    {
        return view('Seniman.karya.create');
    }

    public function storeKarya(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $gambarName = time() . '.' . $request->gambar->extension();
        $request->gambar->storeAs('public/karya_seni', $gambarName);

        KaryaSeni::create([
            'seniman_id' => $seniman->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $gambarName,
        ]);

        return redirect()->route('seniman.dashboard')->with('success', 'Karya seni berhasil ditambahkan!');
    }

    public function editKarya($id)
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('id', $id)->where('seniman_id', $seniman->id)->firstOrFail();

        return view('Seniman.karya.edit', compact('karya'));
    }

    public function updateKarya(Request $request, $id)
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('id', $id)->where('seniman_id', $seniman->id)->firstOrFail();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $karya->judul = $request->judul;
        $karya->deskripsi = $request->deskripsi;
        $karya->harga = $request->harga;

        if ($request->hasFile('gambar')) {
            if ($karya->gambar) {
                Storage::delete('public/karya_seni/' . $karya->gambar);
            }
            $gambarName = time() . '.' . $request->gambar->extension();
            $request->gambar->storeAs('public/karya_seni', $gambarName);
            $karya->gambar = $gambarName;
        }

        $karya->save();

        return redirect()->route('seniman.dashboard')->with('success', 'Karya seni berhasil diperbarui!');
    }

    public function destroyKarya($id)
    {
        $seniman = Auth::guard('seniman')->user();
        $karya = KaryaSeni::where('id', $id)->where('seniman_id', $seniman->id)->firstOrFail();

        if ($karya->gambar) {
            Storage::delete('public/karya_seni/' . $karya->gambar);
        }

        $karya->delete();

        return redirect()->route('seniman.dashboard')->with('success', 'Karya seni berhasil dihapus!');
    }
}
