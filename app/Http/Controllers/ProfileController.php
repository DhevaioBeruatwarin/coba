<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pembeli')->user(); // ambil data pembeli yang sedang login
        return view('pembeli.profile', compact('user'));
    }
}

