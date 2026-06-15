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
    
    <h4 class="font-weight-bold mb-2 mt-2">Tambah Nelayan Baru</h4>

    <form action="{{ route('nelayan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="asal" value="{{ request('asal') }}">
        <div class="form-group mb-4">
            <label class="font-weight-bold text-dark">Foto Profil (Opsional)</label>
            <div class="custom-file">
                <input type="file" name="foto_profil" class="custom-file-input" id="fotoProfil" accept="image/*">
                <label class="custom-file-label" for="fotoProfil"
                    style="border-radius: 12px; border: 2px solid #eaf6fd;">
                    <i class="bi bi-image mr-1"></i>
                    Pilih Foto...
                </label>
            </div>
            <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
            <div id="alert-foto-besar"
                class="alert shadow-sm mt-2"
                style="display: none; border-radius: 12px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill text-danger mr-2"></i>
                    <span style="font-size: 13px;">
                        Ukuran foto terlalu besar. Maksimal 2 MB.
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group mb-4">
            <label class="font-weight-bold">Nama Nelayan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-lg shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" placeholder="Masukkan nama" required>
        </div>

        <div class="form-group mb-5">
            <label class="font-weight-bold">Nomor WA <span class="text-danger">*</span></label>
            <input type="number" name="nomor_hp" class="form-control form-control-lg shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" placeholder="08123456789" required>
        </div>

        <div class="btn-bawah-ganda">
             @if(request('asal') == 'penjualan')
                <a href="{{ route('penjualan.create') }}" class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0" style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
                    Batal
                </a>
            @else
                <a href="{{ route('nelayan.index') }}" class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0" style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
                    Batal
                </a>
            @endif
            <button type="submit" class="btn btn-success btn-lg font-weight-bold shadow-sm m-0" style="border-radius: 15px; flex: 1; padding: 16px 0;">
                Tambah
            </button>
        </div>
    </form>
</div>
<script>
document.getElementById('fotoProfil').addEventListener('change', function() {

    let file = this.files[0];

    if (!file) return;

    let maxSize = 2 * 1024 * 1024; // 2 MB

    if (file.size > maxSize) {

        document.getElementById('alert-foto-besar').style.display = 'block';

        this.value = '';

        document.querySelector('label[for="fotoProfil"]').innerHTML = 'Pilih Foto...';

        setTimeout(() => {
            document.getElementById('alert-foto-besar').style.display = 'none';
        }, 4000);

        return;
    }

    document.getElementById('alert-foto-besar').style.display = 'none';

    document.querySelector('label[for="fotoProfil"]').innerHTML =
    '<i class="bi bi-image-fill text-success mr-1"></i>' + file.name;
});
</script>
@endsection