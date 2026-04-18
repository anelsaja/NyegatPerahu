<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $pengguna = Auth::user();
        return view('profil.index', compact('pengguna'));
    }
}
