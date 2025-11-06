<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Pembeli;

class PembeliController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pembeli');
    }

    // Tampilkan profil pembeli yang sedang login
    public function profil()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data fresh dari database
        $pembeli = Pembeli::find($pembeli->id_pembeli);

        // Set header untuk mencegah cache
        return response()
            ->view('Pembeli.profile', compact('pembeli'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data fresh dari database
        $pembeli = Pembeli::find($pembeli->id_pembeli);

        return view('Pembeli.edit_profil', compact('pembeli'));
    }

    // Simpan perubahan profil
    public function update(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        try {
            // Update langsung ke database
            DB::table('pembeli')
                ->where('id_pembeli', $pembeli->id_pembeli)
                ->update([
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'bio' => $request->bio,
                    'updated_at' => now()
                ]);

            // Refresh model
            $pembeli = Pembeli::find($pembeli->id_pembeli);

            // Update session auth
            Auth::guard('pembeli')->setUser($pembeli);

            return redirect()->route('pembeli.profil')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Upload foto profil
    public function updateFoto(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'foto.required' => 'Silakan pilih foto',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, atau gif',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        try {
            $pembeliData = Pembeli::find($pembeli->id_pembeli);

            // Hapus foto lama jika ada
            if ($pembeliData->foto && Storage::disk('public')->exists('foto_pembeli/' . $pembeliData->foto)) {
                Storage::disk('public')->delete('foto_pembeli/' . $pembeliData->foto);
            }

            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . $pembeli->id_pembeli . '.' . $file->getClientOriginalExtension();
            $file->storeAs('foto_pembeli', $filename, 'public');

            // Update database
            DB::table('pembeli')
                ->where('id_pembeli', $pembeli->id_pembeli)
                ->update([
                    'foto' => $filename,
                    'updated_at' => now()
                ]);

            // Refresh model
            $pembeliData = Pembeli::find($pembeli->id_pembeli);

            // Update session auth
            Auth::guard('pembeli')->setUser($pembeliData);

            return redirect()->route('pembeli.profil')->with('success', 'Foto profil berhasil diperbarui!')->with('_timestamp', time());
        } catch (\Exception $e) {
            return redirect()->route('pembeli.profil')->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }
}
