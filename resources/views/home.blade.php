@extends('layouts.app')
@section('content')
<style>
    body {
        background-color: #ffffff;
    }

    /* TOMBOL TAMBAH DATA (Floating Action Button - Kanan Bawah) */
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
        text-decoration: none !important;
        transition: transform 0.2s;
    }
    .btn-tambah-fab:hover, .btn-tambah-fab:active {
        color: white;
        transform: scale(0.95);
    }

    /* GAYA DAFTAR TRANSAKSI FLAT (Tanpa Card) */
    .transaksi-item {
        border-bottom: 1px solid #f0f2f5;
        padding: 15px 0;
    }

    .info-link {
        text-decoration: none !important;
        color: inherit;
        display: flex;
        flex-grow: 1;
        align-items: center;
    }
</style>

<div class="p-3">
    @if(session('success'))
    <div id="alert-sukses-wa" class="alert alert-success alert-dismissible fade show shadow-sm mt-3" role="alert" style="border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-check-circle-fill mr-1"></i> 
                <strong>{{ session('success') }}</strong>
            </div>
            
            @if(session('link_wa_nelayan'))
                <a href="{{ session('link_wa_nelayan') }}" 
                   target="_blank" 
                   class="btn btn-success btn-sm font-weight-bold shadow" 
                   style="border-radius: 8px; background-color: #25D366; border-color: #25D366;"
                   onclick="document.getElementById('alert-sukses-wa').style.display='none';">
                    <i class="bi bi-whatsapp"></i> Kirim ke WA
                </a>
            @endif
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <h4 class="font-weight-bold mb-4 mt-2">Riwayat Penjualan</h4>

    <h6>Cari berdasarkan tanggal:</h6>

    <form action="{{ route('home') }}" method="GET" class="mb-2">
        <input type="date" 
               name="tanggal" 
               class="form-control form-control-lg font-weight-bold shadow-sm" 
               style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff; "
               value="{{ request('tanggal') }}" 
               max="{{ date('Y-m-d') }}"
               onchange="this.form.submit()"> 
    </form>

    <div class="mt-3">
        @forelse($riwayat_penjualan->sortByDesc('created_at') as $trx)
        <div class="transaksi-item">
            
            <div class="info-link mb-2">
                
                <div class="flex-grow-1 pr-2">
                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 16px;">
                            {{ $trx->nelayan->nama ?? 'Nelayan Dihapus' }}
                        </h6>
                        <small class="text-muted font-weight-bold" style="font-size: 11px;">
                            {{ date('d M Y', strtotime($trx->tanggal)) }}
                        </small>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted text-truncate" style="max-width: 180px; font-size: 13px;">
                            Pengepul: <strong>{{ $trx->detail->pluck('nama_pengepul')->unique()->implode(', ') }}</strong>
                        </small>
                        <strong class="text-success" style="font-size: 14px;">
                            Rp {{ number_format($trx->total_harga - $trx->biaya_admin, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
            </div>
            
            <div class="d-flex w-100" style="gap: 8px;">
                <a href="{{ route('penjualan.show', $trx->penjualan_id) }}" class="btn btn-outline-info font-weight-bold text-center" style="border-radius: 8px; flex: 1; font-size: 13px; padding: 8px 0;">
                    <i class="bi bi-eye-fill d-block mb-1" style="font-size: 16px;"></i> Detail
                </a>

                <a href="{{ route('penjualan.edit', $trx->penjualan_id) }}" class="btn btn-outline-warning font-weight-bold text-center" style="border-radius: 8px; flex: 1; font-size: 13px; padding: 8px 0;">
                    <i class="bi bi-pencil-square d-block mb-1" style="font-size: 16px;"></i> Edit
                </a>
                
                <form action="{{ route('penjualan.destroy', $trx->penjualan_id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');" style="flex: 1; margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger font-weight-bold text-center w-100 h-100" style="border-radius: 8px; font-size: 13px; padding: 8px 0;">
                        <i class="bi bi-trash d-block mb-1" style="font-size: 16px;"></i> Hapus
                    </button>
                </form>
            </div>
            
        </div>
        @empty
        <div class="text-center text-muted p-4 mt-4">
            <i class="bi bi-inbox" style="font-size: 40px; color: #ccc;"></i>
            <p class="mt-2 font-weight-bold">Belum ada riwayat penjualan.</p>
        </div>
        @endforelse
    </div>

    <div style="height: 100px;"></div>

    <a href="{{ route('penjualan.create') }}" class="btn-tambah-fab">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>

@if(session('url_karcis_pdf'))
<script>
    document.addEventListener("DOMContentLoaded", function() {    
        window.location.href = "{{ session('url_karcis_pdf') }}";
    });
</script>
@endif
@endsection