@extends('layouts.app')
@section('content')
<style>
    /* Tombol Utama (Hijau) */
            /* tombol tambah FIX */
    .btn-tambah-fixed {
        position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%);
        width: 98%; background: #08a10b; color: white;
        border-radius: 15px; padding: 20px; font-size: 18px; font-weight: bold;
        text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        border: none; outline: none; z-index: 1050; text-decoration: none !important;
    }
    .btn-tambah-fixed:active { transform: translateX(-50%) scale(0.98); }

    /* Tombol PDF (Merah) */
    .btn-pdf-fixed {
        position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%);
        width: 90%; max-width: 440px; background: #dc3545; color: white;
        border-radius: 15px; padding: 15px; font-size: 18px; font-weight: bold;
        text-align: center; box-shadow: 0 4px 10px rgba(220,53,69,0.3);
        border: none; outline: none; z-index: 1050; text-decoration: none !important;
        display: flex; justify-content: center; align-items: center; gap: 10px;
    }
    .btn-pdf-fixed:active { transform: translateX(-50%) scale(0.98); color: white;}
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-4 mt-2">Laporan Bulanan</h4>

    <div class="card shadow-sm mb-4 border-0" style="border-radius: 15px;">
        <div class="card-body bg-light" style="border-radius: 15px;">
            <form action="{{ route('laporan.index') }}" method="GET">
                <div class="form-group">
                    <label class="font-weight-bold text-muted small">Pilih Nelayan</label>
                    <select name="nelayan_id" class="form-control form-control-lg border-0 shadow-sm" style="border-radius: 12px;" required>
                        <option value="">-- Pilih Nelayan --</option>
                        @foreach($nelayans as $n)
                            <option value="{{ $n->nelayan_id }}" {{ request('nelayan_id') == $n->nelayan_id ? 'selected' : '' }}>{{ $n->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-muted small">Pilih Bulan & Tahun</label>
                    <input type="month" name="bulan" class="form-control form-control-lg border-0 shadow-sm" style="border-radius: 12px;" value="{{ request('bulan') }}" required>
                </div>

                @if($laporan === null)
                    <button type="submit" class="btn-tambah-fixed">
                        <i class="bi bi-eye-fill" style="font-size: 30px;"></i><br>
                        Lihat Laporan
                    </button>
                @else
                    <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold" style="border-radius: 12px; border-style: dashed;">
                        <i class="bi bi-arrow-repeat"></i> Ganti Filter Pencarian
                    </button>
                @endif
            </form>
        </div>
    </div>

    @if($laporan !== null)
        <h6 class="font-weight-bold mb-3 border-bottom pb-2 text-center">Hasil Rekapitulasi</h6>
        
        @if($laporan->isEmpty())
            <div class="alert alert-warning text-center shadow-sm" style="border-radius: 12px;">Tidak ada data transaksi di bulan ini.</div>
        @else
            <div class="card shadow-sm mb-4 border-0 mx-auto" style="border-radius: 15px; background-color: #f8fbfa; max-width: 350px;">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <span class="text-muted small d-block mb-1">Total Kotor</span>
                        <span class="font-weight-bold text-dark" style="font-size: 16px;">Rp {{ number_format($total_kotor, 0, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 pb-3 border-bottom border-secondary">
                        <span class="text-muted small d-block mb-1">Potongan Admin</span>
                        <span class="text-danger font-weight-bold">- Rp {{ number_format($total_admin, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-3">
                        <span class="text-success font-weight-bold d-block mb-1" style="letter-spacing: 1px;">LABA BERSIH</span>
                        <strong class="text-success" style="font-size: 26px;">Rp {{ number_format($laba_bersih, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <h6 class="font-weight-bold mb-3 text-muted" style="font-size: 13px;">Rincian Harian</h6>
            @foreach($laporan as $lap)
                @php 
                    $bersih_harian = $lap->total_harga - $lap->biaya_admin; 
                @endphp
                <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between text-muted small border-bottom pb-2 mb-2">
                            <span><i class="bi bi-calendar3"></i> {{ date('d/m/Y', strtotime($lap->tanggal)) }}</span>
                            <span>Kepada: <strong class="text-dark">{{ $lap->detail->pluck('nama_pengepul')->unique()->implode(', ') }}</strong></span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted">Kotor:</span>
                            <span class="small font-weight-bold">Rp {{ number_format($lap->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted">Admin:</span>
                            <span class="small text-danger">- Rp {{ number_format($lap->biaya_admin, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <span class="font-weight-bold small text-success">Bersih:</span>
                            <span class="font-weight-bold text-success" style="font-size: 15px;">Rp {{ number_format($bersih_harian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
        <a href="{{ route('laporan.pdf', ['nelayan_id' => request('nelayan_id'), 'bulan' => request('bulan')]) }}" class="btn-pdf-fixed">
            <i class="bi bi-file-earmark-pdf-fill" style="font-size: 24px;"></i> Download PDF
        </a>
    @endif
    
</div>
<div style="height: 140px;"></div>
@endsection