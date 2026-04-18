<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Menampilkan halaman desain UI Login
    public function loginView()
    {
        return view('login');
    }

    // Mengarahkan sistem ke halaman Login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menerima balasan data dari Google setelah ibu nelayan memilih akun
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

                // Cek apakah email sudah ada di database. Jika belum, daftarkan akun baru!
            $pengguna = Pengguna::firstOrCreate(
                ['email' => $user->getEmail()],
                [
                    'nama' => $user->getName(),
                    'google_id' => $user->getId(),
                ]
            );

            // Masukkan pengguna ke dalam sesi sistem (Login sukses)
            Auth::login($pengguna);

            // Arahkan ke halaman utama (Home)
            return redirect()->route('home');

        } catch (\Exception $e) {
            // MATIKAN SEMENTARA pesan error generic ini
            // return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google.');

            // TAMPILKAN pesan error aslinya (dd = dump and die)
            dd($e->getMessage());
        }
    }

    // Proses keluar aplikasi
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}