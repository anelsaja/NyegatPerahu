@extends('layouts.app')
@section('content')

<style>
    .bottom-nav {
        display: none !important;
    }

    .btn-bawah-ganda { 
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%; 
        padding: 12px;
        display: flex;
        gap: 10px;
        background-color: #ffffff;
        border-top: 2px solid #f0f0f0;
    }
</style>

<div class="p-3">

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #fde8ec; border: 1px solid #f8c9d2; color: #dc3545;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill mr-2" style="font-size: 1.2rem;"></i> 
            <strong style="font-size: 14px;">{{ session('error') }}</strong>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding-top: 0.6rem;">
            <span aria-hidden="true" style="color: #dc3545;">&times;</span>
        </button>
    </div>
    @endif

    <h4 class="font-weight-bold mb-2 mt-2">Edit Data Nelayan</h4>

    <form action="{{ route('nelayan.update', $nelayan->nelayan_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-4 text-center">
            <div class="mb-3">
                @if($nelayan->foto_profil)
                    <img src="{{ asset('images/nelayan/' . $nelayan->foto_profil) }}" alt="Foto Profil" class="shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #08a10b;">
                @else
                    <div class="shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%; background-color: #08a10b; color: white; font-size: 35px; font-weight: bold; border: 3px solid #eaf6fd;">
                        {{ substr($nelayan->nama, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="custom-file text-left">
                <input type="file" name="foto_profil" class="custom-file-input" id="fotoProfil" accept="image/*">
                <label class="custom-file-label" for="fotoProfil" style="border-radius: 12px; border: 2px solid #eaf6fd;">Ganti Foto (Opsional)...</label>
            </div>
            <small class="text-muted text-left d-block mt-1">Biarkan kosong jika tidak ingin mengubah foto.</small>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold">Nama Nelayan<span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-lg shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" value="{{ $nelayan->nama }}" required>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold">Nomor WA<span class="text-danger">*</span></label>
            <input type="number" name="nomor_hp" class="form-control form-control-lg shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" value="{{ $nelayan->nomor_hp }}" required>
        </div>

        <div class="btn-bawah-ganda">
            <a href="{{ route('nelayan.index') }}" class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0" style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
                Batal
            </a>
            <button type="submit" class="btn btn-warning btn-lg font-weight-bold shadow-sm m-0" style="border-radius: 15px; flex: 1; padding: 16px 0;">
                Simpan Edit
            </button>
        </div>
    </form>
</div>
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("fotoProfil").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection