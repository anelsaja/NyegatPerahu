@extends('layouts.app')
@section('content')
<style>
    /* TOMBOL TAMBAH DATA (Floating Action Button) */
    .btn-tambah-fab {
        position: fixed;
        bottom: 100px;
        right: 20px; 
        background: #08a10b; 
        color: white;
        border-radius: 50%; 
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 1000;
        transition: transform 0.2s;
    }
    .btn-tambah-fab:hover, .btn-tambah-fab:active {
        color: white;
        transform: scale(0.95);
    }

    /* GAYA DAFTAR FLAT */
    .list-item {
        border-bottom: 1px solid grey;
        padding: 13px 0;
    }

    /* Foto Profil Bundar */
    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #08a10b; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: bold;
        color: #fff;
        margin-right: 15px;
    }

    /* Pembungkus Info */
    .info-wrapper {
        display: flex;
        align-items: center;
    }
</style>

<div class="p-3">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert" style="border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-check-circle-fill mr-1"></i> 
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding-top: 0.5rem;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <h4 class="font-weight-bold mb-4 mt-2">Data Nelayan</h4>

    <h6>Cari nama nelayan:</h6>

    <form action="{{ route('nelayan.index') }}" method="GET" class="mb-4">
        <div class="input-group shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff; overflow: hidden;">
            <input type="text" 
                   name="cari" 
                   class="form-control form-control-lg font-weight-bold border-0" 
                   style="color: #495057; background-color: #f8fcff; box-shadow: none; font-size: 1.15rem;"
                   placeholder="Cari nama nelayan..." 
                   value="{{ request('cari') }}">
            <div class="input-group-append">
                <button type="submit" class="btn border-0" style="background-color: #f8fcff; color: #495057">
                    <i class="bi bi-search font-weight-bold"></i>
                </button>
            </div>
        </div>
    </form>

    <div>
        @forelse($nelayans as $n)
        <div class="list-item">
            <div class="info-wrapper mb-3">
                <div class="avatar">
                    {{ substr($n->nama, 0, 1) }}
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 font-weight-bold text-dark" style="font-size: 17px;">
                        {{ $n->nama }}
                    </h6>
                    <small class="text-muted" style="font-size: 15px;">
                        <i class="bi bi-telephone text-info"></i> {{ $n->nomor_hp }}
                    </small>
                </div>
            </div>  
            <div class="d-flex" style="gap: 20px;">
                <a href="{{ route('nelayan.edit', $n->nelayan_id) }}" class="btn btn-warning font-weight-bold text-center" style="border-radius: 8px; flex: 1; font-size: 13px; padding: 8px 0;">
                    <i class="bi bi-pencil-square d-block mb-1" style="font-size: 16px;"></i> Edit
                </a>
                <button type="button" onclick="siapkanHapus('{{ route('nelayan.destroy', $n->nelayan_id) }}')" class="btn btn-danger font-weight-bold text-center w-100 h-100" style="border-radius: 8px; font-size: 13px; padding: 8px 0; background-color: #dc3545; border: none; flex: 1; margin: 0;">
                    <i class="bi bi-trash d-block mb-1" style="font-size: 16px;"></i> Hapus
                </button>
            </div>       
        </div>
        @empty
        <div class="text-center text-muted p-4 mt-4">
            <i class="bi bi-people" style="font-size: 40px; color: #ccc;"></i>
            <p class="mt-2 font-weight-bold">
                {{ request('cari') ? 'Nama nelayan tidak ditemukan.' : 'Belum ada data nelayan.' }}
            </p>
            @if(request('cari'))
            <a href="{{ route('nelayan.index') }}" class="btn btn-md btn-light mt-2 font-weight-bold" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;">Tampilkan Semua</a>
            @endif
        </div>
        @endforelse
    </div>

    <div style="height: 200px;"></div>

    <a href="{{ route('nelayan.create') }}" class="btn-tambah-fab" title="Tambah Nelayan Baru">
        <i class="bi bi-person-plus-fill"></i>
    </a>
</div>
<div class="modal fade" id="modalHapusNelayan">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-dark">Hapus Nelayan</h5>
            </div>
            <div class="modal-body">
                <p class="text-muted">Apakah Ibu yakin ingin menghapus data nelayan ini?</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light shadow-sm font-weight-bold" data-dismiss="modal" style="border-radius: 12px; color: #6c757d; width: 120px;">
                    Batal
                </button>
                <form id="formHapusAjaib" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger shadow-sm font-weight-bold" style="border-radius: 12px; background-color: #dc3545; width: 120px;">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function siapkanHapus(urlHapusData) {
        // 1. Suntikkan alamat rute data yang akan dihapus ke dalam Form Pop-up
        document.getElementById('formHapusAjaib').action = urlHapusData;
        
        // 2. Munculkan Pop-up secara visual
        $('#modalHapusNelayan').modal('show');
    }
</script>
@endsection