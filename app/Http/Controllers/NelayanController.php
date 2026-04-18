<?php

namespace App\Http\Controllers;

use App\Nelayan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NelayanController extends Controller
{
    // Menampilkan daftar nelayan
    public function index()
    {
        // Ambil nelayan yang dimiliki oleh ibu nelayan yang sedang login saja
        $nelayans = Nelayan::where('pengguna_id', Auth::id())->get();
        return view('nelayan.index', compact('nelayans'));
    }

    // Menampilkan form tambah nelayan
    public function create()
    {
        return view('nelayan.create');
    }

    // Menyimpan nelayan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'nullable|string|max:15',
        ]);

        Nelayan::create([
            'pengguna_id' => Auth::id(),
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
        ]);

        return redirect()->route('nelayan.index')->with('success', 'Nelayan berhasil ditambahkan!');
    }

    // Menghapus data nelayan
    public function destroy($id)
    {
        Nelayan::where('nelayan_id', $id)->where('pengguna_id', Auth::id())->delete();
        return redirect()->route('nelayan.index')->with('success', 'Data nelayan dihapus!');
    }

    // Menampilkan halaman form edit
    public function edit($id)
    {
        // Cari data nelayan berdasarkan ID dan pastikan itu milik ibu yang sedang login
        $nelayan = Nelayan::where('nelayan_id', $id)
                    ->where('pengguna_id', Auth::id())
                    ->firstOrFail();

        return view('nelayan.edit', compact('nelayan'));
    }

    // Menyimpan perubahan data ke database
    public function update(Request $request, $id)
    {
        $nelayan = Nelayan::where('nelayan_id', $id)
                    ->where('pengguna_id', Auth::id())
                    ->firstOrFail();

        // Update nama nelayan (Tambahkan kolom lain di bawah 'nama' jika di tabelmu ada no_hp/alamat)
        $nelayan->update([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
        ]);

        return redirect()->route('nelayan.index')->with('success', 'Data Nelayan berhasil diperbarui!');
    }

    
    
}
