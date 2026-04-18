<!doctype html>
<html>
  <head>
    <title>Laporan Penjualan - {{ $nelayan->nama }}</title>
    <style>
      /* Pengaturan Font dan Dasar */
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

      /* Tabel Data Transaksi */
      .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
      }
      .data-table th,
      .data-table td {
        border: 1px solid #ddd;
        padding: 8px 6px;
      }
      .data-table th {
        background-color: #08a10b;
        color: #ffffff;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 10px;
      }

      /* Pewarnaan Baris & Kolom */
      .data-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
      }
      .text-center {
        text-align: center;
      }
      .text-right {
        text-align: right;
      }
      .text-danger {
        color: #dc3545;
      }
      .text-success {
        color: #28a745;
        font-weight: bold;
      }

      /* Baris Total (Footer Tabel) */
      .total-row td {
        font-weight: bold;
        font-size: 12px;
      }
      .bg-light-green {
        background-color: #e8f5e9;
      }
      .bg-dark-green {
        background-color: #08a10b;
        color: black;
      }
    </style>
  </head>
  <body>
    <div class="header-nyegat">
      <div class="title-logo">Nyegat<br /><span>Perahu.</span></div>
    </div>

    <div class="header">
      <h2>REKAPITULASI PENJUALAN HASIL LAUT</h2>
      <p>Aplikasi Pencatatan Penjualan Hasil Laut Nelayan</p>
    </div>

    <table class="info-table">
      <tr>
        <td width="15%">Nama Nelayan</td>
        <td width="35%">
          : <span class="info-value">{{ $nelayan->nama }}</span>
        </td>
        <td width="20%">Bulan Laporan</td>
        <td width="30%">: <span class="info-value">{{ $nama_bulan }}</span></td>
      </tr>
      <tr>
        <td>No. Handphone</td>
        <td>
          : <span class="info-value">{{ $nelayan->nomor_hp ?? '-' }}</span>
        </td>
        <td>Dicetak Oleh</td>
        <td>: <span class="info-value">{{ Auth::user()->nama }}</span></td>
      </tr>
    </table>

    <table class="data-table">
      <thead>
        <tr>
          <th width="4%">No</th>
          <th width="12%">Tanggal</th>
          <th width="15%">Pengepul</th>
          <th width="29%">Rincian Tangkapan</th>
          <th width="13%" class="text-right">Total Kotor</th>
          <th width="12%" class="text-right">Biaya Admin</th>
          <th width="15%" class="text-right">Laba Bersih</th>
        </tr>
      </thead>
      <tbody>
        @forelse($laporan as $index => $lap) @php $bersih_per_hari =
        $lap->total_harga - $lap->biaya_admin; @endphp
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td class="text-center">
            {{ date('d/m/Y', strtotime($lap->tanggal)) }}
          </td>
          <td>
            {{ $lap->detail->pluck('nama_pengepul')->unique()->implode(', ') }}
          </td>
          <td>
            @foreach($lap->detail as $item) • {{ $item->jenis_hasil_laut }}
            <br />
            @endforeach
          </td>
          <td class="text-right">
            Rp {{ number_format($lap->total_harga, 0, ',', '.') }}
          </td>
          <td class="text-right text-danger">
            {{ $lap->biaya_admin > 0 ? '- Rp ' .
            number_format($lap->biaya_admin, 0, ',', '.') : '-' }}
          </td>
          <td class="text-right text-success">
            Rp {{ number_format($bersih_per_hari, 0, ',', '.') }}
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center" style="padding: 20px">
            Tidak ada data penjualan pada bulan ini.
          </td>
        </tr>
        @endforelse @if($laporan->isNotEmpty())
        <tr class="total-row bg-light-green">
          <td colspan="4" class="text-right" style="border-right: none">
            AKUMULASI KOTOR & ADMIN :
          </td>
          <td class="text-right">
            Rp {{ number_format($total_kotor, 0, ',', '.') }}
          </td>
          <td class="text-right text-danger">
            - Rp {{ number_format($total_admin, 0, ',', '.') }}
          </td>
          <td
            class="text-right"
            style="
              background-color: #fff;
              border-bottom: none;
              border-right: none;
            "
          ></td>
        </tr>

        <tr class="total-row bg-dark-green">
          <td colspan="6" class="text-right" style="font-size: 14px">
            TOTAL PENDAPATAN BERSIH :
          </td>
          <td class="text-right" style="font-size: 14px">
            Rp {{ number_format($laba_bersih, 0, ',', '.') }}
          </td>
        </tr>
        @endif
      </tbody>
    </table>
  </body>
</html>
