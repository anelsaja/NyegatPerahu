<?php

namespace App\Http\Controllers;

use App\Nelayan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NelayanController extends Controller
{
    // Menampilkan daftar nelayan
    public function index(Request $request)
    {
        // Mulai query untuk mengambil data nelayan milik pengguna (ibu-ibu) yang sedang login
        $query = Nelayan::where('pengguna_id', Auth::id());

        // Cek apakah ada input pencarian dari kolom "cari"
        if ($request->has('cari') && $request->cari != '') {
            // Filter berdasarkan nama yang mirip dengan yang diketik
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

        // Ambil datanya (bisa ditambahkan paginate() jika datanya ribuan)
        $nelayans = $query->orderBy('nama', 'asc')->get();

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
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. LOGIKA ANTI-DUPLIKAT (Baru)
        // Cek apakah di akun pengguna ini sudah ada nelayan dengan nama DAN nomor HP yang persis sama
        $duplikat = Nelayan::where('pengguna_id', Auth::id())
                            ->where('nama', $request->nama)
                            ->where('nomor_hp', $request->nomor_hp)
                            ->exists();

        if ($duplikat) {
            // Jika ada, kembalikan ke form sebelumnya beserta input yang sudah diketik dan pesan error
            return back()->withInput()->with('error', 'Gagal! Nelayan dengan nama dan nomor HP tersebut sudah pernah ditambahkan.');
        }

        // 2. Siapkan wadah data dasar
        $data = [
            'pengguna_id' => Auth::id(),
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
        ];

        // 3. Logika Proses Upload Foto (Jika Ada)
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            // Buat nama file unik agar tidak bentrok jika namanya sama
            $nama_file = time() . '_' . $file->getClientOriginalName();
            // Pindahkan file ke folder public/images/nelayan/
            $file->move(public_path('images/nelayan'), $nama_file);
            
            // Masukkan nama file tersebut ke dalam wadah data
            $data['foto_profil'] = $nama_file;
        }

        // 4. Eksekusi simpan ke database (menggunakan wadah $data yang sudah disiapkan)
        $nelayan = Nelayan::create($data);

        // 5. ALUR BARU: Kembali ke halaman asal (home/tambah penjualan) 
        // sambil membawa ID dan Nama nelayan yang baru dibuat
        if ($request->asal == 'penjualan') {
            // Jika datang dari penjualan, kembalikan ke form penjualan (Step 1)
           return redirect()->route('penjualan.create')->with([
            'nelayan_baru_id' => $nelayan->nelayan_id,
            'nelayan_baru_nama' => $nelayan->nama,
            'success' => 'Nelayan berhasil ditambahkan! silakan pilih!'
           ]);
        } else {
            // Jika datang dari menu nelayan biasa, kembalikan ke tabel nelayan
            return redirect()->route('nelayan.index')->with('success', 'Data nelayan berhasil disimpan!');
        }
    }

    // Menghapus data nelayan
    public function destroy($id)
    {
        Nelayan::where('nelayan_id', $id)->where('pengguna_id', Auth::id())->delete();
        return redirect()->route('nelayan.index')->with('success', 'Data nelayan berhasil dihapus!');
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
        // 1. Validasi Input (Wajib menyertakan aturan untuk foto_profil)
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. LOGIKA ANTI-DUPLIKAT UNTUK EDIT (Baru)
        // Cek apakah nama dan no HP ini sudah dipakai oleh nelayan LAIN (kecuali nelayan yang sedang diedit ini)
        $duplikat = Nelayan::where('pengguna_id', Auth::id())
                            ->where('nama', $request->nama)
                            ->where('nomor_hp', $request->nomor_hp)
                            ->where('nelayan_id', '!=', $id) // <-- Kunci pembedanya ada di sini
                            ->exists();

        if ($duplikat) {
            return back()->withInput()->with('error', 'Gagal update! Nama dan nomor HP tersebut sudah dipakai oleh data nelayan Anda yang lain.');
        }

        // Cari data nelayan berdasarkan ID dan pastikan itu milik ibu yang sedang login
        $nelayan = Nelayan::where('nelayan_id', $id)
                    ->where('pengguna_id', Auth::id())
                    ->firstOrFail();

        // 2. Siapkan wadah data awal untuk di-update
        $data = [
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
        ];

        // 3. Logika Proses Upload & Penggantian Foto Profil
        if ($request->hasFile('foto_profil')) {
            
            // JIKA ADA FOTO LAMA: Hapus filenya dari folder public agar memori tidak penuh
            if ($nelayan->foto_profil && file_exists(public_path('images/nelayan/' . $nelayan->foto_profil))) {
                unlink(public_path('images/nelayan/' . $nelayan->foto_profil));
            }

            // PROSES SIMPAN FOTO BARU
            $file = $request->file('foto_profil');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/nelayan'), $nama_file);
            
            // Masukkan nama file baru ke dalam array data yang akan di-update
            $data['foto_profil'] = $nama_file;
        }

        // 4. Jalankan perintah update dengan array data yang sudah matang
        $nelayan->update($data);

        return redirect()->route('nelayan.index')->with('success', 'Data Nelayan berhasil diperbarui!');
    }
}
