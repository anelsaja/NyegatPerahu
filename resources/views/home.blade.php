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
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h4 class="font-weight-bold mb-4 mt-2">Riwayat Penjualan</h4>

    <form action="{{ route('home') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" max="{{ date('Y-m-d') }}">
            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit">Cari</button>
            </div>
        </div>
    </form>

    @foreach($riwayat_penjualan->sortByDesc('created_at') as $trx)
    <div class="card mb-3 shadow-lg border-0" style="border-radius: 12px; background-color: #ffffff;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                <strong class="text-dark">{{ $trx->nelayan->nama ?? 'Nelayan Dihapus' }}</strong>
                <small class="text-muted">{{ date('d M Y', strtotime($trx->tanggal)) }}</small>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div style="font-size: 13px;">Pengepul: <strong>{{ $trx->detail->pluck('nama_pengepul')->unique()->implode(', ') }}</strong></div>
                </div>
                <div class="text-right">
                    <div class="text-muted" style="font-size: 11px;">Total</div>
                    <strong class="text-info" style="font-size: 16px;">Rp {{ number_format($trx->total_harga - $trx->biaya_admin, 0, ',', '.') }}</strong>
                </div>
            </div>
            
            <div class="d-flex mt-3 pt-2 border-top">

                <!-- DETAIL (lebih besar) -->
                <a href="{{ route('penjualan.show', $trx->penjualan_id) }}" 
                class="btn btn-info shadow-sm py-3 mr-2" 
                style="border-radius: 12px; font-size: 14px; flex: 2;">
                    <i class="bi bi-eye-fill"></i><br>
                    Detail
                </a>

                <!-- EDIT -->
                <a href="{{ route('penjualan.edit', $trx->penjualan_id) }}" 
                class="btn btn-warning shadow-sm py-3 mr-2" 
                style="border-radius: 12px; font-size: 14px; flex: 1;">
                    <i class="bi bi-pencil-square"></i><br>
                    Edit
                </a>

                <!-- HAPUS -->
                <form action="{{ route('penjualan.destroy', $trx->penjualan_id) }}" 
                    method="POST" 
                    style="flex: 1;"
                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini beserta seluruh rinciannya?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger w-100 shadow-sm py-3" 
                            style="border-radius: 12px; font-size: 14px;">
                        <i class="bi bi-trash"></i><br>
                        Hapus
                    </button>
                </form>

            </div>

        </div>
    </div>
    @endforeach

    <div style="height: 240px;"></div>

    <!-- TOMBOL TAMBAH FIX -->
    <a href="{{ route('penjualan.create') }}" class="btn-tambah-fixed">
        <div style="font-size:30px;"><i class="bi bi-plus-square"></i></div>
        Tambah Data Penjualan Baru
    </a>

    @if($riwayat_penjualan->isEmpty())
        <div class="text-center text-muted mt-4">Belum ada riwayat penjualan.</div>
    @endif
</div>
@if(session('buka_pdf'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Otomatis membuka tab baru ke link PDF yang dikirim dari Controller
        window.open("{{ session('buka_pdf') }}", "_blank");
    });
</script>
@endif
@endsection