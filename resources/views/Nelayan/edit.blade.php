@extends('layouts.app')
@section('content')

<style>
    /* Sembunyikan navigasi bawah */
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 100px !important; }

    .btn-bawah-ganda { 
        position: fixed; 
        bottom: 0; 
        left: 0; 
        width: 100%; 
        background-color: #ffffff; 
        padding: 12px; 
        border-top: 1px solid #f0f0f0; 
        z-index: 1050; 
        display: flex; 
        gap: 10px;
    }

    .btn-bawah-ganda a, 
    .btn-bawah-ganda button {
        flex: 1; /* INI KUNCI supaya 50:50 */
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        border: none; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
    }

    .form-control-custom {
        border-radius: 12px;
        background-color: #f8f9fa;
        border: 1px solid #eee;
        padding: 12px 15px;
        font-size: 14px;
    }
</style>

<div class="p-3">

    <h4 class="font-weight-bold mb-4 mt-2">Edit Data Nelayan</h4>

    <form action="{{ route('nelayan.update', $nelayan->nelayan_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label class="font-weight-bold text-muted small ml-1">Nama Lengkap</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text border-0 bg-transparent text-muted"><i class="bi bi-person"></i></span>
                </div>
                <input type="text" name="nama" class="form-control form-control-lg border-0 shadow-sm" style="border-radius: 12px; background-color: #f8f9fa;" value="{{ $nelayan->nama }}" required>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold text-muted small ml-1">Nomor Telepon / WA</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text border-0 bg-transparent text-muted"><i class="bi bi-telephone"></i></span>
                </div>
                <input type="number" name="nomor_hp" class="form-control form-control-lg border-0 shadow-sm" style="border-radius: 12px; background-color: #f8f9fa;" value="{{ $nelayan->nomor_hp }}" >
            </div>
        </div>

        <div class="btn-bawah-ganda">
            <a href="{{ route('nelayan.index') }}" class="btn btn-light text-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-warning text-white">
                <i class="bi bi-floppy-fill mr-2"></i>Simpan Edit
            </button>
        </div>
    </form>
</div>
@endsection