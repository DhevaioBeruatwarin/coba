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
    // Form register
    public function showRegisterForm()
    {
        return view('register');
    }

    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pembeli|unique:seniman|unique:admin',
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

        // redirect ke login
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Pembeli
        $pembeli = Pembeli::where('email', $credentials['email'])->first();
        if ($pembeli && Hash::check($credentials['password'], $pembeli->password)) {
            Auth::guard('pembeli')->login($pembeli);
            $request->session()->regenerate();
            return redirect()->route('pembeli.dashboard');
        }

        // Seniman
        $seniman = Seniman::where('email', $credentials['email'])->first();
        if ($seniman && Hash::check($credentials['password'], $seniman->password)) {
            Auth::guard('seniman')->login($seniman);
            $request->session()->regenerate();
            return redirect()->route('seniman.dashboard');
        }

        // Admin
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    // Logout universal
    public function logout(Request $request)
    {
        foreach (['pembeli', 'seniman', 'admin'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
