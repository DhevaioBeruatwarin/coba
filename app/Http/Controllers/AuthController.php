<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Seniman;
use App\Models\Admin;
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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');


        $pembeli = Pembeli::where('email', $credentials['email'])->first();
        if ($pembeli && Hash::check($credentials['password'], $pembeli->password)) {
            Auth::guard('pembeli')->login($pembeli);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }


        $seniman = Seniman::where('email', $credentials['email'])->first();
        if ($seniman && Hash::check($credentials['password'], $seniman->password)) {
            Auth::guard('seniman')->login($seniman);
            $request->session()->regenerate();
            return redirect()->intended('/Seniman/dashboard');
        }


        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->intended('/Admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('pembeli')->check()) {
            Auth::guard('pembeli')->logout();
        } elseif (Auth::guard('seniman')->check()) {
            Auth::guard('seniman')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}