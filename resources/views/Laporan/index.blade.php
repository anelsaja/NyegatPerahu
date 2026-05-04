@extends('layouts.app')
@section('content')
<style>
    body {
        background-color: #ffffff;
    }

    /* GAYA DAFTAR FLAT */
    .list-item {
        border-bottom: 1px solid #f0f2f5;
        padding: 15px 0;
    }

    /* Foto Profil Bundar (Di sini kita gunakan untuk TANGGAL) */
    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #08a10b; /* Warna hijau konsisten */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        color: #fff;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .info-wrapper {
        display: flex;
        align-items: center;
    }

    /* FAB PDF Merah (Tombol Bulat Melayang Kanan Bawah) */
    .btn-pdf-fab {
        position: fixed;
        bottom: 100px;
        right: 20px; 
        background: #dc3545; /* Warna Merah khas PDF */
        color: white;
        border-radius: 50%; 
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 12px rgba(220,53,69,0.4);
        z-index: 1000;
        text-decoration: none !important;
        transition: transform 0.2s;
    }
    .btn-pdf-fab:hover, .btn-pdf-fab:active {
        color: white;
        transform: scale(0.95);
    }
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-4 mt-2">Laporan Bulanan</h4>

    <form action="{{ route('laporan.index') }}" method="GET" class="mb-4">
        <div class="form-group mb-3">
            <label class="font-weight-bold text-muted small">Pilih Nelayan</label>
            <select name="nelayan_id" class="form-control form-control-lg font-weight-bold shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" required>
                <option value="">-- Pilih Nelayan --</option>
                @foreach($nelayans as $n)
                    <option value="{{ $n->nelayan_id }}" {{ request('nelayan_id') == $n->nelayan_id ? 'selected' : '' }}>{{ $n->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label class="font-weight-bold text-muted small">Pilih Bulan & Tahun</label>
            <input type="month" name="bulan" class="form-control form-control-lg font-weight-bold shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" value="{{ request('bulan') }}" required>
        </div>

        @if($laporan === null)
            <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm py-3 mt-4" style="border-radius: 12px; background-color: #08a10b; border: none; font-size: 16px;">
                <i class="bi bi-search mr-1"></i> Lihat Laporan
            </button>
        @else
            <button type="submit" class="btn btn-outline-success btn-block font-weight-bold py-3 mt-4" style="border-radius: 12px; border-width: 2px; font-size: 16px; color: #08a10b; border-color: #08a10b;">
                <i class="bi bi-arrow-repeat mr-1"></i> Terapkan Filter Baru
            </button>
        @endif
    </form>

    @if($laporan !== null)
        <hr class="my-4" style="border-color: #f0f2f5;">

        <h6 class="font-weight-bold mb-3 text-dark">Hasil Rekapitulasi</h6>
        
        @if($laporan->isEmpty())
            <div class="text-center text-muted p-4 mt-2">
                <i class="bi bi-inbox" style="font-size: 40px; color: #ccc;"></i>
                <p class="mt-2 font-weight-bold">Tidak ada transaksi di bulan ini.</p>
            </div>
        @else
            <div class="shadow-sm mb-4 p-3" style="border-radius: 15px; background-color: #f8fcff; border: 1px solid #eaf6fd;">
                <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-2">
                    <span class="text-muted font-weight-bold" style="font-size: 14px;">Total Kotor</span>
                    <span class="font-weight-bold text-dark">Rp {{ number_format($total_kotor, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-2 mb-2">
                    <span class="text-muted font-weight-bold" style="font-size: 14px;">Potongan Admin</span>
                    <span class="font-weight-bold text-danger">- Rp {{ number_format($total_admin, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-1">
                    <span class="text-success font-weight-bold" style="font-size: 15px;">Laba Bersih</span>
                    <strong class="text-success" style="font-size: 20px;">Rp {{ number_format($laba_bersih, 0, ',', '.') }}</strong>
                </div>
            </div>

            <h6 class="font-weight-bold mb-3 mt-4 text-dark">Rincian Harian</h6>
            
            <div>
                @foreach($laporan as $lap)
                    @php 
                        $bersih_harian = $lap->total_harga - $lap->biaya_admin; 
                    @endphp
                    <div class="list-item">
                        
                        <div class="info-wrapper mb-2">
                            
                            <div class="flex-grow-1 pr-2">
                                <div class="d-flex justify-content-between align-items-baseline mb-1">
                                    <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 16px;">
                                        {{ date('d M Y', strtotime($lap->tanggal)) }}
                                    </h6>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted text-truncate" style="max-width: 170px; font-size: 13px;">
                                        Pengepul: <strong>{{ $lap->detail->pluck('nama_pengepul')->unique()->implode(', ') }}</strong>
                                    </small>
                                    <strong class="text-success" style="font-size: 14px;">
                                        Rp {{ number_format($bersih_harian, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between px-3 py-2 mt-2" style="background-color: #f8f9fa; border-radius: 8px;">
                            <span class="text-danger font-weight-bold" style="font-size: 12px;">Admin: -Rp {{ number_format($lap->biaya_admin, 0, ',', '.') }}</span>
                        </div>
                        
                    </div>
                @endforeach
            </div>
        @endif
        
        <a href="{{ route('laporan.pdf', ['nelayan_id' => request('nelayan_id'), 'bulan' => request('bulan')]) }}" class="btn-pdf-fab" title="Download PDF">
            <i class="bi bi-file-earmark-pdf-fill"></i>
        </a>
    @endif
    
</div>

<div style="height: 100px;"></div>
@endsection