@extends('layouts.app')
@section('content')
<style>
    .bottom-nav {
        display: none !important;
    }

    .info-table {
        width: 100%;
        font-size: 13px;
    }

    .info-table td {
        padding: 4px 0;
        border-bottom: 1px dashed #ccc;
    }

    .info-table td:last-child {
        text-align: right;
        font-weight: bold;
    }

    .btn-bawah-ganda { 
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%; 
        padding: 12px;
        display: flex;
        gap: 10px;
        background-color: #ffffff;
        border-top: 2px solid #f0f0f0;
    }
</style>

<div class="p-3">
    <div id="alert-wa"
        class="alert alert-success alert-dismissible fade show shadow-sm mt-1"
        style="border-radius:12px; display:none;">

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-check-circle-fill mr-1"></i>
                <strong>Karcis berhasil dibuat.</strong>
            </div>

            <a href="{{ $penjualan->link_wa }}"
            target="_blank"
            class="btn btn-success btn-sm font-weight-bold shadow"
            style="border-radius:8px; background:#25D366; border-color:#25D366;"
            onclick="document.getElementById('alert-wa').style.display='none';">
                <i class="bi bi-whatsapp"></i>
                Kirim ke WA
            </a>
        </div>

        <button type="button"
                class="close"
                data-dismiss="alert">
            <span>&times;</span>
        </button>

    </div>
    <h4 class="font-weight-bold mb-2 mt-2">Detail Data Penjualan</h4>
    <table class="info-table mb-3">
        <tr><td class="text-muted">Tanggal</td><td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->locale('id')->translatedFormat('d F Y') }}</td></tr>
        <tr><td class="text-muted">Nama Nelayan</td><td>{{ $penjualan->nelayan->nama ?? '-' }}</td></tr>
        <tr><td class="text-muted">Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
    </table>

    <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Rincian Tangkapan</h6>

    @foreach($detail_dikelompokkan as $key => $items)
        @php
            [$pengepul, $statusPengepul] = explode('|', $key);

            $totalPerPengepul = 0;

            $badgeColor = ($statusPengepul == 'Lunas')
                ? 'badge-success'
                : 'badge-danger';
        @endphp

        <div class="mb-4 border-bottom pb-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center flex-wrap">
                    <h6 class="font-weight-bold mb-0 mr-2">
                        <i class="bi bi-shop mr-1"></i> {{ $pengepul }}
                    </h6>
                    <span class="badge {{ $badgeColor }} rounded-pill px-2 py-1">
                        {{ $statusPengepul }}
                    </span>
                </div>
            </div>
            <table class="info-table mt-1">
                @foreach($items as $item)
                <tr>
                    <td class="text-secondary"><i class="bi bi-record-circle text-muted" style="font-size: 8px; margin-right: 5px;"></i> {{ $item->jenis_hasil_laut }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                </tr>
                @php $totalPerPengepul += $item->harga; @endphp
                @endforeach

                <tr>
                    <td class="text-right text-muted pt-2 border-top">Sub-total:</td>
                    <td class="pt-2 text-info border-top font-weight-bold" style="font-size: 15px;">
                        Rp {{ number_format($totalPerPengepul, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Ringkasan Akhir</h6>

    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Total Tangkapan</span>
                <strong style="font-size: 16px;">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong>
            </div>

            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-secondary">
                <label class="font-weight-bold">Biaya Admin</label>
                <strong class="text-danger" style="font-size: 16px;">- Rp {{ number_format($penjualan->biaya_admin, 0, ',', '.') }}</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-success font-weight-bold" style="letter-spacing: 0.5px;">TOTAL AKHIR</span>
                <strong class="text-success" style="font-size: 24px;">
                    Rp {{ number_format($penjualan->total_bersih, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>
</div>
<div style="height: 120px;"></div>

<div class="btn-bawah-ganda">
    <a href="{{ route('home') }}" class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0" style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
        Kembali
    </a>
    <button type="button"
        onclick="buatKarcis()"
        class="btn btn-primary btn-lg font-weight-bold shadow-sm m-0"
        style="border-radius:15px; flex:1; padding:16px 0;">
        Buat Karcis
    </button>
</div>
<iframe id="downloadFrame" style="display:none;"></iframe>
<script>
    function buatKarcis() {

        // Download PDF tanpa membuka tab baru
        document.getElementById('downloadFrame').src =
            "{{ route('penjualan.cetak', $penjualan->penjualan_id) }}";

        // Tampilkan alert
        document.getElementById("alert-wa").style.display = "block";
    }
</script>
@endsection