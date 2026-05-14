@extends('layouts.app')
@section('content')
<style>
    /* GAYA DAFTAR FLAT */
    .list-item {
        border-bottom: 1px solid #f0f2f5;
        padding: 15px 0;
    }

    .info-wrapper {
        display: flex;
        align-items: center;
    }

    /* FAB WA (Hijau) */
    .btn-wa-fab {
        position: fixed;
        bottom: 100px;
        right: 20px; 
        background: #25D366; /* Warna Hijau WA */
        color: white;
        border-radius: 50%; 
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        z-index: 1000;
        border: none;
        outline: none;
        transition: transform 0.2s;
    }
    .btn-wa-fab:hover, .btn-wa-fab:active {
        color: white;
        transform: scale(0.95);
    }
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-4 mt-2">Laporan Bulanan</h4>

    <form action="{{ route('laporan.index') }}" method="GET" class="mb-4">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Nama Nelayan</label>
            <select name="nelayan_id" class="form-control form-control-lg font-weight-bold shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" required>
                <option value="">-- Pilih Nelayan --</option>
                @foreach($nelayans as $n)
                    <option value="{{ $n->nelayan_id }}" {{ request('nelayan_id') == $n->nelayan_id ? 'selected' : '' }}>{{ $n->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label class="font-weight-bold">Bulan & Tahun</label>
            <input type="month" 
                   name="bulan" 
                   class="form-control form-control-lg font-weight-bold shadow-sm" 
                   style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" 
                   value="{{ request('bulan') }}" 
                   max="{{ date('Y-m') }}" 
                   required>
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
        <hr class="mb-4" style="border-color: black">

        <h6 class="font-weight-bold mb-3 text-dark">Hasil Rekapitulasi</h6>
        
        @if($laporan->isEmpty())
            <div class="text-center text-muted p-4 mt-2">
                <i class="bi bi-inbox" style="font-size: 50px; color: #ccc;"></i>
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
                    <span class="text-success font-weight-bold" style="font-size: 15px;">Total Pendapatan</span>
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
                                
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 16px;">
                                        {{ date('d M Y', strtotime($lap->tanggal)) }}
                                    </h6>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="text-muted" style="font-size: 13px;">
                                        <span class="d-block mb-1">Pengepul:</span>
                                        @foreach($lap->detail->pluck('nama_pengepul')->unique() as $pengepul)
                                            <strong class="d-block text-dark mb-1" style="padding-left: 8px; border-left: 2px solid #08a10b;">
                                                {{ $pengepul }}
                                            </strong>
                                        @endforeach
                                    </div>
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
        
        @php
            // Cari data nelayan yang sedang dipilih dari dropdown
            $nelayan_terpilih = $nelayans->where('nelayan_id', request('nelayan_id'))->first();
            $link_wa_bulanan = null;

            // Jika nelayan ketemu dan punya nomor HP, buat link WA-nya
            if($nelayan_terpilih && !empty($nelayan_terpilih->nomor_hp)) {
                $hp = preg_replace('/[^0-9]/', '', $nelayan_terpilih->nomor_hp);
                if (substr($hp, 0, 1) == '0') {
                    $hp = '62' . substr($hp, 1);
                }
                
                // Format angka dan bulan
                $rupiah_bersih = number_format($laba_bersih, 0, ',', '.');
                $bulan_format = \Carbon\Carbon::parse(request('bulan').'-01')->locale('id')->translatedFormat('F Y');
                
                // Susun pesan bulanan
                $pesan = "Halo Pak {$nelayan_terpilih->nama}, ini rekapitulasi penjualan hasil laut Bapak untuk bulan {$bulan_format}. Total pendapatan bersihnya adalah *Rp {$rupiah_bersih}*. File laporan PDF-nya silakan diunduh ya Pak.";
                
                $link_wa_bulanan = "https://wa.me/{$hp}?text=" . urlencode($pesan);
            }
        @endphp

        @if($link_wa_bulanan)
            <button type="button" 
                    onclick="cetakDanKirimWa('{{ route('laporan.pdf', ['nelayan_id' => request('nelayan_id'), 'bulan' => request('bulan')]) }}', '{!! $link_wa_bulanan !!}')" 
                    class="btn-wa-fab" 
                    title="Cetak PDF & Kirim WA">
                <i class="bi bi-whatsapp"></i>
            </button>
        @else
            <a href="{{ route('laporan.pdf', ['nelayan_id' => request('nelayan_id'), 'bulan' => request('bulan')]) }}" class="btn-pdf-fab" title="Download PDF">
                <i class="bi bi-file-earmark-pdf-fill"></i>
            </a>
        @endif
    @endif
</div>

<div style="height: 180px;"></div>

<script>
    function cetakDanKirimWa(urlPdf, urlWa) {
        // 1. Perintahkan browser membuka tab WA baru
        window.open(urlWa, '_blank');
        
        // 2. Beri jeda 1 detik lalu jalankan unduh laporan PDF
        setTimeout(function() {
            window.location.href = urlPdf;
        }, 1000);
    }
</script>
@endsection