@extends('layouts.app')
@section('content')
<style>
    /* Avatar Profil Besar */
    .avatar-profile {
        width: 100px;
        height: 100px;
        background-color: #08a10b;
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
        bottom: 100px;
        right: 20px; 
        background: #dc3545; 
        color: white;
        border-radius: 50%; 
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        text-decoration: none !important;
    }
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-2 mt-2">Profil Saya</h4>

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

    <div class="modal fade" id="modalLogout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mx-3" style="max-width: 100%;">
            <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">
                        Keluar Aplikasi
                    </h5>
                </div>
                <div class="modal-body pt-3">
                    <div class="alert shadow-sm mb-3"
                        style="border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-circle-fill text-danger mr-2"
                            style="font-size: 18px;"></i>
                            <span style="font-size: 13px;">
                                Apakah Ibu yakin ingin keluar dari aplikasi sekarang?
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button"
                            class="btn btn-light shadow-sm font-weight-bold"
                            data-dismiss="modal"
                            style="border-radius: 15px; padding: 10px 15px;">
                        Batal
                    </button>
                    <a href="{{ route('logout') }}"
                    class="btn btn-danger shadow-sm font-weight-bold px-4"
                    style="border-radius: 15px; padding: 10px 15px;">
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection