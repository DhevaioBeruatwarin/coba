<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seniman;

class SenimanController extends Controller
{
    // Tampilkan profil seniman yang sedang login
    public function profile()
    {
        $seniman = Auth::guard('seniman')->user();

        if (!$seniman) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('Seniman.profile', compact('seniman'));
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $seniman = Auth::guard('seniman')->user();
        return view('Seniman.edit_profil', compact('seniman'));
    }

    // Simpan perubahan profil
    public function update(Request $request)
    {
        $seniman = Auth::guard('seniman')->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'nullable|string|max:20',
            'bidang' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        $seniman->update($request->only('nama', 'email', 'telepon', 'bidang', 'bio', 'alamat'));

        return redirect()->route('seniman.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
