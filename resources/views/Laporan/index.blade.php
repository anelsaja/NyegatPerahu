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

    /* FAB */
    .btn-wa-fab {
        position: fixed;
        bottom: 100px;
        right: 20px; 
        background: #25D366;
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

    /* Gaya khusus saat kartu nelayan diklik / aktif */
    .btn-kotak.active {
        border-color: #007bff !important;
        background-color: #e9f2ff !important;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }
    
    .btn-kotak.active .icon-box {
        background-color: #007bff !important;
        color: white !important;
    }

    .grid-btn {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .fotoprofil {
        width: 60px;
        height: 60px;
        border: 3px solid #333;
        border-radius: 15px;
        margin: 0 auto 10px auto;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fotoprofil img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-kotak { 
        background-color: #e0e0e0;
        border-radius: 15px;
        padding: 15px 10px; 
        text-align: center;
        font-weight: bold;
    }
</style>

<div class="p-3">
    @php
        $nelayan_terpilih = $nelayans->where('nelayan_id', request('nelayan_id'))->first();
        $link_wa_bulanan = null;

        if($nelayan_terpilih && !empty($nelayan_terpilih->nomor_hp)) {
            $hp = preg_replace('/[^0-9]/', '', $nelayan_terpilih->nomor_hp);

            if(substr($hp,0,1)=='0'){
                $hp='62'.substr($hp,1);
            }

            if(request('bulan')){
                $rupiah_bersih = isset($laba_bersih)
                    ? number_format($laba_bersih,0,',','.')
                    : '0';
                $bulan_format = \Carbon\Carbon::parse(request('bulan').'-01')
                                ->locale('id')
                                ->translatedFormat('F Y');
                $pesan = "Halo Pak {$nelayan_terpilih->nama}, ini rekapitulasi penjualan hasil laut Bapak untuk bulan {$bulan_format}. Total pendapatan bersihnya adalah *Rp {$rupiah_bersih}*. File laporan PDF-nya silakan diunduh ya Pak.";
                $link_wa_bulanan = "https://wa.me/{$hp}?text=".urlencode($pesan);
            }
        }
    @endphp
    <div id="alert-wa-laporan"
        class="alert alert-success alert-dismissible fade shadow-sm"
        style="display:none; border-radius:12px;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-check-circle-fill mr-1"></i>
                <strong>Laporan berhasil diunduh!</strong>
            </div>
            <a href="{!! $link_wa_bulanan !!}"
            target="_blank"
            class="btn btn-success btn-sm font-weight-bold shadow"
            style="border-radius:8px; background:#25D366; border-color:#25D366;">
                <i class="bi bi-whatsapp"></i> Kirim ke WA
            </a>
        </div>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>

    <h4 class="font-weight-bold mb-2 mt-2">Laporan Bulanan</h4>

    <div id="alert-filter" class="alert shadow-sm"
        style="display:none; border-radius:12px; background-color:#fde8ec; border-left:2px solid #dc3545;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill text-danger mr-2"></i>
            <span style="font-size:13px;">
                Silakan pilih nelayan dan periode terlebih dahulu.
            </span>
        </div>
    </div>

    <form id="formLaporan" action="{{ route('laporan.index') }}" method="GET" class="mb-4" novalidate>
        <div class="form-group mb-3">
            <label class="font-weight-bold">Nama Nelayan</label>
            <input type="hidden" name="nelayan_id" id="input_nelayan_id" value="{{ request('nelayan_id') }}" required>
            
            <div class="grid-btn">
                @forelse($nelayans as $n)
                    <div class="btn-kotak {{ request('nelayan_id') == $n->nelayan_id ? 'active' : '' }}" 
                         id="kartu-nelayan-{{ $n->nelayan_id }}" 
                         onclick="pilihNelayanLaporan({{ $n->nelayan_id }})">
                        <div class="fotoprofil">
                            @if($n->foto_profil)
                                <img src="{{ asset('images/nelayan/' . $n->foto_profil) }}" alt="Foto {{ $n->nama }}">
                            @else
                                <i class="bi bi-person-fill" style="font-size: 35px;"></i>
                            @endif
                        </div>                        
                        <span class="font-weight-bold">{{ $n->nama }}</span>
                    </div>
                @empty
                    <div class="text-center text-muted p-4 w-100" style="grid-column: span 2;">
                        <i class="bi bi-person-x" style="font-size: 40px; color: #ccc;"></i>
                        <p class="mt-2 font-weight-bold mb-2">Belum ada data nelayan.</p>
                    </div>
                @endforelse
            </div>
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
        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Tangkapan</span>
                    <strong style="font-size: 16px;">Rp {{ number_format($total_kotor, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-secondary">
                    <label class="font-weight-bold">Biaya Admin</label>
                    <strong class="text-danger" style="font-size: 16px;">- Rp {{ number_format($total_admin, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success font-weight-bold" style="letter-spacing: 0.5px;">TOTAL AKHIR</span>
                    <strong class="text-success" style="font-size: 24px;">
                        Rp {{ number_format($laba_bersih, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        </div>

        <h6 class="font-weight-bold mb-3 mt-4 text-dark">Rincian Harian</h6>
            
        <div>
            @foreach($laporan as $lap)
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
                                Rp {{ number_format($lap->total_bersih,0,',','.') }}
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
        @if($link_wa_bulanan && $laporan->isNotEmpty())
            <button type="button"
                    onclick="downloadLaporan()"
                    class="btn-wa-fab"
                <i class="bi bi-file-earmark-arrow-down-fill"></i>
            </button>
        @endif
    @endif
</div>

<div style="height: 180px;"></div>

<script>
    function downloadLaporan() {
        let nelayan = document.getElementById('input_nelayan_id').value;
        let bulan = document.querySelector('input[name="bulan"]').value;
        let url = "{{ route('laporan.pdf') }}"
                + "?nelayan_id=" + encodeURIComponent(nelayan)
                + "&bulan=" + encodeURIComponent(bulan);

        // Download PDF pada tab yang sama
        window.location.href = url;

        // Tampilkan alert WA setelah download dimulai
        setTimeout(function () {
            let alertBox = document.getElementById('alert-wa-laporan');
            alertBox.style.display = 'block';
            alertBox.classList.add('show');

        }, 1000);
    }

    function pilihNelayanLaporan(nelayanId) {
        // 1. Masukkan ID nelayan yang dipilih ke dalam input tersembunyi
        document.getElementById('input_nelayan_id').value = nelayanId;
        // 2. Hapus class 'active' dari SEMUA kartu nelayan
        let semuaKartu = document.querySelectorAll('.grid-btn .btn-kotak');
        semuaKartu.forEach(function(kartu) {
            kartu.classList.remove('active');
        });
        // 3. Tambahkan class 'active' HANYA ke kartu yang baru saja diklik
        document.getElementById('kartu-nelayan-' + nelayanId).classList.add('active');
    }

    document.getElementById('formLaporan').addEventListener('submit', function(e) {
        let nelayan = document.getElementById('input_nelayan_id').value;
        let bulan = document.querySelector('input[name="bulan"]').value;

        if (!nelayan || !bulan) {
            e.preventDefault();
            let alertBox = document.getElementById('alert-filter');
            alertBox.style.display = 'block';
            alertBox.classList.add('show');
            setTimeout(() => {
                alertBox.style.display = 'none';
                alertBox.classList.remove('show');
            }, 4000);
        }
    });
</script>
@endsection