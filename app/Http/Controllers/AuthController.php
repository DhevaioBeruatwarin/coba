<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Seniman;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pembeli|unique:seniman',
            'password' => 'required|confirmed|min:6',
            'no_hp' => 'required|numeric|digits_between:10,12',
            'role' => 'required|in:seniman,pembeli',
        ]);


        if ($request->role === 'pembeli') {
            Pembeli::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'alamat' => null,
                'no_hp' => $request->no_hp,
            ]);
        } else {
            Seniman::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'alamat' => null,
                'no_hp' => $request->no_hp,
            ]);
        }

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }


    //buat login

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $user = auth()->user();

        if (auth()->attempt($credentials)) {
            if ($user === 'pembeli') {
                return redirect()->route('/dashboard');
            } elseif ($user === 'seniman') {
                return redirect()->route('Seniman/dashboard');
            } elseif ($user === 'admin') {
                return redirect()->route('Admin/dashboard');
            }
        }

    }
}
