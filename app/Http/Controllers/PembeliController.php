<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembeliController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pembeli');
    }

    public function profil()
    {
        $pembeli = Auth::guard('pembeli')->user();
        return view('pembeli.profile', compact('pembeli'));
    }
}
