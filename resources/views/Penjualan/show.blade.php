@extends('layouts.app')
@section('content')

<style>
    /* Sembunyikan navigasi bawah KHUSUS di halaman ini */
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 100px !important; }

    .info-table { width: 100%; margin-bottom: 15px; font-size: 13px; }
    .info-table td { padding: 4px 0; border-bottom: 1px dashed #eee; }
    .info-table td:last-child { text-align: right; font-weight: bold; }
    
    .btn-bawah-ganda { 
        position: fixed; 
        bottom: 0; 
        left: 0; 
        width: 100%; 
        padding: 12px; 
        z-index: 1050; 
        display: flex; 
        gap: 10px;
    }

    .btn-bawah-ganda a, 
    .btn-bawah-ganda button {
        flex: 1; /* otomatis 50:50 */
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        text-align: center;
        border: none; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
    }

</style>


<div class="p-3">
    <h4 class="font-weight-bold mb-4 mt-2">Rincian Transaksi</h4>
    <span class="badge {{ $penjualan->status_pembayaran == 'Lunas' ? 'badge-success' : 'badge-warning' }}">{{ $penjualan->status_pembayaran }}</span>
    
    <table class="info-table mb-4">
        <tr><td class="text-muted">Tanggal</td><td>{{ date('d M Y', strtotime($penjualan->tanggal)) }}</td></tr>
        <tr><td class="text-muted">Nama Nelayan</td><td>{{ $penjualan->nelayan->nama ?? '-' }}</td></tr>
        <tr><td class="text-muted">Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
    </table>

    <h6 class="font-weight-bold mt-4 mb-3 pb-2 border-bottom">Tangkapan per Pengepul</h6>

    @foreach($detail_dikelompokkan as $pengepul => $items)
    <div class="mb-4 border-bottom pb-2">
        <h6 class="font-weight-bold d-inline-block text-primary">Pengepul: {{ $pengepul }}</h6>
        <table class="info-table mt-1">
            @php $totalPerPengepul = 0; @endphp
            
            @foreach($items as $item)
            <tr>
                <td class="text-dark">{{ $item->jenis_hasil_laut }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
            </tr>
            @php $totalPerPengepul += $item->harga; @endphp
            @endforeach
            
            <tr>
                <td class="text-right text-muted pt-2">Sub-total:</td>
                <td class="pt-2 text-info" style="font-size: 15px;">Rp {{ number_format($totalPerPengepul, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endforeach

    @if($penjualan->biaya_admin > 0)
    <div class="d-flex justify-content-between mt-3 pt-3 border-top text-danger font-weight-bold">
        <span>Potongan Biaya Admin</span>
        <span>- Rp {{ number_format($penjualan->biaya_admin, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="d-flex justify-content-between mt-3 pt-3 border-top font-weight-bold" style="font-size: 18px;">
        <span>TOTAL DITERIMA</span>
        <span class="text-success">
            Rp {{ number_format($penjualan->total_harga - $penjualan->biaya_admin, 0, ',', '.') }}
        </span>
    </div>
</div>

<div class="btn-bawah-ganda">
    <a href="{{ route('home') }}" class="btn btn-light text-secondary">
        Kembali
    </a>
    <a href="{{ route('penjualan.cetak', $penjualan->penjualan_id) }}" class="btn btn-primary text-white">
        <i class="bi bi-printer-fill mr-2"></i> Cetak Ulang
    </a>
</div>
        <div class="btn-bawah-ganda">
            <a href="{{ route('home') }}" class="btn btn-light text-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-black">
                <i class="bi bi-floppy-fill mr-1"></i> Simpan Edit
            </button>
        </div>
@endsection