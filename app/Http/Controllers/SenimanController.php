<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Seniman;
use App\Models\KaryaSeni;

class SenimanController extends Controller
{
    // Tampilkan profil seniman yang sedang login
    public function profile()
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data fresh dari database
        $seniman = Seniman::find($seniman->id_seniman);

        // Tambahan: agar foto profil bisa muncul di navbar
        session(['seniman_foto' => $seniman->foto]);

        return response()
            ->view('Seniman.profile', compact('seniman'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $seniman = Seniman::find($seniman->id_seniman);

        return view('Seniman.edit_profil', compact('seniman'));
    }

    // Simpan perubahan profil (nama & email tidak bisa diubah)
    public function update(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'no_hp' => 'nullable|string|max:20',
            'bidang' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        try {
            DB::table('seniman')
                ->where('id_seniman', $seniman->id_seniman)
                ->update([
                    'no_hp' => $request->no_hp,
                    'bidang' => $request->bidang,
                    'bio' => $request->bio,
                    'alamat' => $request->alamat,
                    'updated_at' => now()
                ]);

            $seniman = Seniman::find($seniman->id_seniman);
            Auth::guard('seniman')->setUser($seniman);

            // Update foto navbar jika ada
            session(['seniman_foto' => $seniman->foto]);

            return redirect()->route('seniman.profil')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Upload foto profil
    public function updateFoto(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
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
            $senimanData = Seniman::find($seniman->id_seniman);

            // Hapus foto lama
            if ($senimanData->foto && Storage::disk('public')->exists('foto_seniman/' . $senimanData->foto)) {
                Storage::disk('public')->delete('foto_seniman/' . $senimanData->foto);
            }

            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . $seniman->id_seniman . '.' . $file->getClientOriginalExtension();
            $file->storeAs('foto_seniman', $filename, 'public');

            DB::table('seniman')
                ->where('id_seniman', $seniman->id_seniman)
                ->update([
                    'foto' => $filename,
                    'updated_at' => now()
                ]);

            $senimanData = Seniman::find($seniman->id_seniman);
            Auth::guard('seniman')->setUser($senimanData);

            // Tambahan: simpan di session supaya navbar langsung update
            session(['seniman_foto' => $filename]);

            return redirect()
                ->route('seniman.profil')
                ->with('success', 'Foto profil berhasil diperbarui!')
                ->with('_timestamp', time());
        } catch (\Exception $e) {
            return redirect()->route('seniman.profil')->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
        }
    }

    public function karyaSaya()
    {
        $seniman = auth()->guard('seniman')->user();
        $karya = KaryaSeni::where('id_seniman', $seniman->id_seniman)->get();
        return view('Seniman.karya.index', compact('karya'));
    }

}
