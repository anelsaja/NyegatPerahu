<?php

namespace App\Http\Controllers;

use App\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar: Ambil data penjualan milik ibu yang sedang login
        // 'with('nelayan')' digunakan agar kita bisa memanggil nama nelayan nanti
        $query = Penjualan::with('nelayan')->where('pengguna_id', Auth::id());

        // Jika ada pencarian tanggal dari form
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Urutkan dari yang paling baru
        $riwayat_penjualan = $query->orderBy('tanggal', 'desc')->get();

        return view('home', compact('riwayat_penjualan'));
    }
}
