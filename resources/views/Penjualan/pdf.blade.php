<!DOCTYPE html>
<html>
<head>
    <title>Karcis Penjualan - {{ $penjualan->nelayan->nama }} - {{ \Carbon\Carbon::parse($penjualan->tanggal)->locale('id')->translatedFormat('d F Y') }}</title>
    <style>
        body {
            font-family: "Montserrat", sans-serif;
        }

        .header-nyegat {
            background-color: #d8efff;
            padding: 15px;
            text-align: center;
        }
        .title-logo {
            font-weight: 900;
            font-size: 20px;
            color: #333;
            line-height: 1.2;
        }
        .title-logo span {
            color: #5bc0de;
        }

        /* Kop Laporan */
        .header {
            text-align: center;
            padding: 30px 0;
            margin-bottom: 20px;
            border-bottom: 3px solid #08a10b;
        }
        .header h2 {
            margin: 0;
            color: #08a10b;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0;
            color: #555;
            font-size: 13px;
        }

        /* Tabel Informasi Nelayan */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px 0;
            font-size: 12px;
        }
        .info-value {
            font-weight: bold;
            color: #000;
        }
        
        /* Gaya Pengelompokan Pengepul */
        .group-section { margin-bottom: 15px; border: 1px solid #ddd; padding: 8px; border-radius: 5px; }
        .pengepul-name { font-weight: bold; background-color: #f0f0f0; padding: 4px; border-bottom: 1px solid #000; display: block; margin-bottom: 5px; }
        
        .item-table { width: 100%; border-collapse: collapse; }
        .item-table td { padding: 3px 0; font-size: 12px;}
        .text-right { text-align: right; }
        .subtotal { border-top: 1px dashed #999; font-weight: bold; margin-top: 5px; padding-top: 2px; font-size: 12px;}
        
        /* Box Catatan */
        .catatan-box {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            font-size: 12px;
            color: #555;
        }

        .total-section { margin-top: 20px; border-top: 2px solid #000; padding-top: 10px; }
        .grand-total { font-size: 16px; font-weight: bold; color: #000; }
        .footer { text-align: center; margin-top: 30px; font-style: italic; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header-nyegat">
      <div class="title-logo">Nyegat<br /><span>Perahu.</span></div>
    </div>

    <div class="header">
      <h2>REKAPITULASI PENJUALAN</h2>
      <p>Aplikasi Pencatatan Penjualan Hasil Laut Nelayan</p>
    </div>

    <table class="info-table">
      <tr>
        <td width="15%">Nama Nelayan</td>
        <td width="35%">
          : <span class="info-value">{{ $penjualan->nelayan->nama }}</span>
        </td>
        <td width="20%">Tanggal</td>
        <td width="30%">: <span class="info-value">{{ \Carbon\Carbon::parse($penjualan->tanggal)->locale('id')->translatedFormat('d F Y') }}</span></td>
      </tr>
      <tr>
        <td>No. Handphone</td>
        <td>
          : <span class="info-value">{{ $penjualan->nelayan->nomor_hp ?? '-' }}</span>
        </td>
        <td>Dicetak Oleh</td>
        <td>: <span class="info-value">{{ Auth::user()->nama }}</span></td>
      </tr>
    </table>

    {{-- LOGIKA PENGELOMPOKAN DATA & PERHITUNGAN LANGSUNG --}}
    @php
        $groupedDetails = $penjualan->detail->groupBy('nama_pengepul');
        $totalKotorGlobal = 0; // Inisialisasi variabel penyimpan total bersih
    @endphp

    @foreach($groupedDetails as $namaPengepul => $items)
        <div class="group-section">
            <span class="pengepul-name">
                Pengepul: {{ $namaPengepul }}
            </span>
            
            <table class="item-table">
                @php $subtotal = 0; @endphp
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->jenis_hasil_laut }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @php 
                        $subtotal += $item->harga; 
                        $totalKotorGlobal += $item->harga; // Tambahkan langsung ke kalkulator utama
                    @endphp
                @endforeach
            </table>
            <div class="text-right subtotal">
                Sub-total: Rp {{ number_format($subtotal, 0, ',', '.') }}
            </div>
        </div>
    @endforeach

    @if(!empty($penjualan->catatan))
    <div class="catatan-box">
        <strong>Catatan:</strong><br>
        {{ $penjualan->catatan }}
    </div>
    @endif

    <div class="total-section">
        <table class="info-table">
            <tr>
                <td>Total Tangkapan</td>
                <td class="text-right">Rp {{ number_format($totalKotorGlobal, 0, ',', '.') }}</td>
            </tr>
            <tr style="color: #d9534f;">
                <td>Biaya Admin</td>
                <td class="text-right">- Rp {{ number_format($penjualan->biaya_admin, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td style="padding-top: 10px;">TOTAL AKHIR</td>
                <td class="text-right" style="padding-top: 10px;">Rp {{ number_format($totalKotorGlobal - $penjualan->biaya_admin, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>