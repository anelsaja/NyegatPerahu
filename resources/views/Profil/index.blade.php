@extends('layouts.app')
@section('content')
<style>
    body {
        background-color: #ffffff;
    }

    /* Avatar Profil Besar */
    .avatar-profile {
        width: 100px;
        height: 100px;
        background-color: #08a10b; /* Warna utama hijau */
        color: white;
        font-size: 40px;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(8, 161, 11, 0.2);
    }

    /* TOMBOL KELUAR MELAYANG (FAB Merah) */
    .btn-logout-fab {
        position: fixed;
        bottom: 100px; /* Sejajar dengan FAB di halaman lain */
        right: 20px; 
        background: #dc3545; /* Warna Merah (Danger) */
        color: white;
        border-radius: 50%; 
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        z-index: 1000;
        text-decoration: none !important;
        transition: transform 0.2s;
    }
    .btn-logout-fab:hover, .btn-logout-fab:active {
        color: white;
        transform: scale(0.95);
    }
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-4 mt-2">Profil Saya</h4>

    <div class="text-center mb-4 mt-5 pt-3">
        <div class="avatar-profile mb-4">
            {{ strtoupper(substr($pengguna->nama, 0, 1)) }}
        </div>
        <h4 class="font-weight-bold text-dark mb-1">{{ $pengguna->nama }}</h4>
        <p class="text-muted">{{ $pengguna->email }}</p>
    </div>

    <a href="javascript:void(0)" 
       class="btn-logout-fab" 
       title="Keluar Akun"
       data-toggle="modal" 
       data-target="#modalLogout">
        <i class="bi bi-box-arrow-right" style="margin-left: 4px;"></i>
    </a>

    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered"> <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
          
          <div class="modal-header border-bottom-0 pb-0">
            <h5 class="modal-title font-weight-bold text-dark" id="modalLogoutLabel">Konfirmasi Keluar</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body pt-3 pb-4">
            <p class="text-muted font-weight-bold mb-0">Apakah Ibu yakin ingin keluar dari aplikasi sekarang?</p>
          </div>
          
          <div class="modal-footer border-top-0 pt-0">
            <button type="button" class="btn btn-light shadow-sm font-weight-bold" data-dismiss="modal" style="border-radius: 10px; color: #6c757d;">
                Batal
            </button>
            
            <a href="{{ route('logout') }}" class="btn btn-danger shadow-sm font-weight-bold px-4" style="border-radius: 10px; background-color: #dc3545;">
                Ya, Keluar <i class="bi bi-box-arrow-right ml-1"></i>
            </a>
          </div>
          
        </div>
      </div>
    </div>
</div>
@endsection