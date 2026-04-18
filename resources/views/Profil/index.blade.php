@extends('layouts.app')
@section('content')
<style>
        /* tombol tambah FIX */
    .btn-tambah-fixed {
        position: fixed;
        bottom: 100px; /* di atas bottom nav */
        left: 50%;
        transform: translateX(-50%);
        width: 98%;
        background: red;
        color: white;
        border-radius: 15px;
        padding: 20px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        text-decoration: none !important;
    }
    .btn-tambah-fixed:hover {
    text-decoration: none !important;
    color: white;
}
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-5 mt-2">Profil Saya</h4>

    <div class="mb-4">
        <div style="width: 100px; height: 100px; background-color: #5bc0de; color: white; font-size: 40px; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; font-weight: bold;" class="shadow">
            {{ strtoupper(substr($pengguna->nama, 0, 1)) }}
        </div>
    </div>

    <h4 class="font-weight-bold">{{ $pengguna->nama }}</h4>
    <p class="text-muted mb-5">{{ $pengguna->email }}</p>
    
    <a href="{{ route('logout') }}" 
    class="btn-tambah-fixed text-center"
    onclick="return confirm('Apakah Anda yakin ingin keluar?')">

        <div style="font-size:30px;"><i class="bi bi-box-arrow-left"></i></div>
        Keluar Akun

    </a>
</div>
@endsection