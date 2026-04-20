@extends('layouts.app')
@section('content')

<style>
    /* Sembunyikan navigasi bawah KHUSUS di halaman ini */
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 120px !important; background-color: #f4f7f6; }

    .info-table { width: 100%; margin-bottom: 0; font-size: 13px; }
    .info-table td { padding: 6px 0; border-bottom: 1px dashed #eee; }
    .info-table td:last-child { text-align: right; font-weight: bold; }
    .info-table tr:last-child td { border-bottom: none; }
    
    .btn-bawah-ganda { 
        position: fixed; bottom: 0; left: 0; width: 100%; 
        padding: 15px; z-index: 1050; display: flex; gap: 12px; 
        background-color: #ffffff; border-top: 1px solid #f0f0f0;
        justify-content: center;
    }

    .btn-bawah-ganda a, .btn-bawah-ganda button {
        flex: 1; max-width: 240px; padding: 14px; border-radius: 12px; 
        font-weight: 700; text-align: center; border: none; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
    }

    /* Gaya Card Baru untuk Pengepul */
    .card-pengepul {
        background-color: #ffffff;
        border-radius: 14px;
        border: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        margin-bottom: 16px;
        overflow: hidden;
    }
    .card-pengepul-header {
        background-color: #f8fbfa;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="p-3">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="font-weight-bold mb-0 text-dark">Detail Transaksi</h4>
            <small class="text-muted">Rincian struk penjualan</small>
        </div>
        <div style="width: 50px; height: 50px; background-color: #eaf6fd; color: #5bc0de; border-radius: 14px; display: flex; justify-content: center; align-items: center; font-size: 22px;">
            <i class="bi bi-receipt"></i>
        </div>
    </div>
    
    <div class="card-pengepul p-3 mb-4 border" style="border-color: #e0e0e0 !important;">
        <table class="info-table">
            <tr><td class="text-muted">Tanggal</td><td>{{ date('d M Y', strtotime($penjualan->tanggal)) }}</td></tr>
            <tr><td class="text-muted">Nama Nelayan</td><td class="text-primary" style="font-size: 15px;">{{ $penjualan->nelayan->nama ?? '-' }}</td></tr>
            <tr><td class="text-muted">Dicatat Oleh</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>
    </div>

    <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Tangkapan per Pengepul</h6>

    @foreach($detail_dikelompokkan as $pengepul => $items)
        @php 
            $totalPerPengepul = 0; 
            // Mengambil status pembayaran dari item pertama milik pengepul ini
            $statusPengepul = $items->first()->status_pembayaran ?? 'Lunas';
            $badgeColor = ($statusPengepul == 'Lunas') ? 'badge-success' : 'badge-warning';
        @endphp

        <div class="card-pengepul">
            <div class="card-pengepul-header">
                <h6 class="font-weight-bold mb-0 text-dark">
                    <i class="bi bi-person-badge text-primary mr-1"></i> {{ $pengepul }}
                </h6>
                <span class="badge {{ $badgeColor }} px-2 py-1" style="font-size: 11px; border-radius: 6px;">
                    {{ $statusPengepul }}
                </span>
            </div>
            
            <div class="p-3">
                <table class="info-table">
                    @foreach($items as $item)
                    <tr>
                        <td class="text-secondary"><i class="bi bi-record-circle text-muted" style="font-size: 8px; margin-right: 5px;"></i> {{ $item->jenis_hasil_laut }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalPerPengepul += $item->harga; @endphp
                    @endforeach
                    
                    <tr>
                        <td class="text-right text-muted pt-3 border-top mt-2">Sub-total:</td>
                        <td class="pt-3 text-info border-top mt-2" style="font-size: 15px;">
                            Rp {{ number_format($totalPerPengepul, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

    <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Ringkasan Akhir</h6>

    <div class="card-pengepul p-3 mb-4 border-0" style="background-color: #f8fbfa;">
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small font-weight-bold">Total Kotor</span>
            <span class="font-weight-bold text-dark">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
        </div>
        
        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-secondary">
            <span class="text-muted small font-weight-bold">Potongan Biaya Admin</span>
            <span class="text-danger font-weight-bold">- Rp {{ number_format($penjualan->biaya_admin, 0, ',', '.') }}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-success font-weight-bold" style="letter-spacing: 0.5px;">TOTAL DITERIMA</span>
            <strong class="text-success" style="font-size: 24px;">
                Rp {{ number_format($penjualan->total_harga - $penjualan->biaya_admin, 0, ',', '.') }}
            </strong>
        </div>
    </div>

</div>

<div class="btn-bawah-ganda">
    <a href="{{ route('home') }}" class="btn btn-light text-secondary" style="background-color: #f8f9fa;">
        <i class="bi bi-arrow-left mr-1"></i> Kembali
    </a>
    <a href="{{ route('penjualan.cetak', $penjualan->penjualan_id) }}" class="btn btn-primary text-white shadow-sm">
        <i class="bi bi-printer-fill mr-1"></i> Cetak Ulang Struk
    </a>
</div>
@endsection