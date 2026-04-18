<!doctype html>
<html>
<head>
    <title>Detail Transaksi</title>
    <style>
      body {
        font-family: "Montserrat", sans-serif;
      }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
            color: #777;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
        }

        .info-table td {
            padding: 4px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title">DETAIL TRANSAKSI</div>
    <div class="subtitle">Pencatatan Hasil Laut Nelayan</div>
</div>

<div class="box">
    <table class="info-table">
        <tr>
            <td width="30%">Nama Nelayan</td>
            <td>: <b>{{ $penjualan->nelayan->nama }}</b></td>
        </tr>
        <tr>
            <td>No HP</td>
            <td>: {{ $penjualan->nelayan->nomor_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ date('d/m/Y', strtotime($penjualan->tanggal)) }}</td>
        </tr>
        <tr>
            <td>Pengepul</td>
            <td>: {{ $penjualan->detail->pluck('nama_pengepul')->unique()->implode(', ') }}</td>
        </tr>
    </table>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis Hasil Laut</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($penjualan->detail as $i => $item)
        <tr>
            <td style="text-align:center">{{ $i + 1 }}</td>
            <td>{{ $item->jenis_hasil_laut }}</td>
            <td class="text-right">
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach

        <tr class="total">
            <td colspan="2" class="text-right">Total Kotor</td>
            <td class="text-right">
                Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="total">
            <td colspan="2" class="text-right">Biaya Admin</td>
            <td class="text-right">
                - Rp {{ number_format($penjualan->biaya_admin, 0, ',', '.') }}
            </td>
        </tr>

        <tr class="total">
            <td colspan="2" class="text-right">Pendapatan Bersih</td>
            <td class="text-right">
                Rp {{ number_format($penjualan->total_harga - $penjualan->biaya_admin, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>