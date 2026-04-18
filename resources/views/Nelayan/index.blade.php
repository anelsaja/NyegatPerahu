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
        background: #08a10b;
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
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h4 class="font-weight-bold mb-0">Data Nelayan</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($nelayans as $n)
    <div class="card mb-3 shadow-sm" style="border-radius: 15px; border-left: 5px solid #17a2b8;">
        <div class="card-body">
            <h5 class="font-weight-bold mb-1">{{ $n->nama }}</h5>
            <p class="text-muted mb-3"><i class="bi bi-telephone"></i>  {{ $n->nomor_hp ?? 'Tidak ada nomor HP' }}</p>
            <div class="d-flex justify-content-between mt-3">
                <!-- EDIT -->
                <a href="{{ route('nelayan.edit', $n->nelayan_id) }}" 
                class="btn btn-warning flex-fill mr-2 shadow-sm py-3" 
                style="border-radius: 12px; font-size: 16px;">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <!-- HAPUS -->
                <form action="{{ route('nelayan.destroy', $n->nelayan_id) }}" method="POST" class="flex-fill">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger w-100 shadow-sm py-3" 
                            style="border-radius: 12px; font-size: 16px;"
                            onclick="return confirm('Yakin ingin menghapus nelayan ini?')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <div style="height: 240px;"></div>

    <!-- TOMBOL TAMBAH FIX -->
    <a href="{{ route('nelayan.create') }}" class="btn-tambah-fixed">
        <div style="font-size:30px;"><i class="bi bi-plus-square"></i></div>
        Tambah Data Nelayan Baru
    </a>

    @if($nelayans->isEmpty())
        <div class="text-center text-muted mt-5">Belum ada data nelayan.</div>
    @endif
</div>
@endsection