@extends('layouts.app')
@section('content')

<style>
    /* Sembunyikan navigasi bawah KHUSUS di halaman ini */
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 120px !important; }

    .info-table { width: 100%; margin-bottom: 15px; font-size: 13px; }
    .info-table td { padding: 4px 0; border-bottom: 1px dashed #eee; }
    .info-table td:last-child { text-align: right; font-weight: bold; }
    
    .btn-bawah-ganda { 
        position: fixed; bottom: 0; left: 0; width: 100%; 
        padding: 12px; z-index: 1050; display: flex; gap: 10px; background-color: #ffffff;
        border-top: 1px solid #f0f0f0;
    }

    .btn-bawah-ganda a, .btn-bawah-ganda button {
        flex: 1; padding: 14px; border-radius: 12px; font-weight: 600;
        text-align: center; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
    }

    .input-struk {
        border: 1px solid #e0e0e0; background-color: #fafafa;
        border-radius: 8px; font-size: 14px; font-weight: bold;
    }
    .input-struk:focus { background-color: #fff; border-color: #5bc0de; box-shadow: none; }
    
    .card-ikan {
        background-color: #ffffff; border-radius: 12px; 
        border: 1px solid #eaeaea; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .card-input-ikan {
        background-color: #eaf6fd;
        border: 1px solid #b8daff;
        border-radius: 8px;
        padding: 4px 10px;
    }
    .card-input-ikan input {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        color: #0056b3;
        padding: 0;
    }
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-3 mt-2">Edit Transaksi</h4>

    <form action="{{ route('penjualan.update', $penjualan->penjualan_id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="tanggal" value="{{ $penjualan->tanggal }}">
        <input type="hidden" name="nelayan_id" value="{{ $penjualan->nelayan_id }}">

        <table class="info-table mb-4">
            <tr><td class="text-muted">Tanggal</td><td>{{ date('d M Y', strtotime($penjualan->tanggal)) }}</td></tr>
            <tr><td class="text-muted">Nama Nelayan</td><td>{{ $penjualan->nelayan->nama ?? '-' }}</td></tr>
            <tr><td class="text-muted">Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <h6 class="font-weight-bold mt-4 mb-3 pb-2 border-bottom">Rincian Tangkapan & Pembayaran</h6>

        <div id="area-hasil-laut">
            @foreach($penjualan->detail as $index => $item)
            <div class="mb-3 p-3 card-ikan" id="baris-ikan-{{ $index }}">
                
                <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-2">
                    <select name="hasil_laut[{{ $index }}][pengepul]" class="form-control form-control-sm input-struk mr-2 border-0 bg-light" style="flex: 1.2;" required>
                        <option value="Kaji Arip" {{ $item->nama_pengepul == 'Kaji Arip' ? 'selected' : '' }}>Kaji Arip</option>
                        <option value="BBI" {{ $item->nama_pengepul == 'BBI' ? 'selected' : '' }}>BBI</option>
                        <option value="Tarom" {{ $item->nama_pengepul == 'Tarom' ? 'selected' : '' }}>Tarom</option>
                        <option value="Pramono" {{ $item->nama_pengepul == 'Pramono' ? 'selected' : '' }}>Pramono</option>
                        <option value="TPI Banyutowo" {{ $item->nama_pengepul == 'TPI banyutowo' ? 'selected' : '' }}>TPI Banyutowo</option>
                        <option value="Rossa" {{ $item->nama_pengepul == 'Rossa' ? 'selected' : '' }}>Rossa</option>
                        <option value="Rini" {{ $item->nama_pengepul == 'Rini' ? 'selected' : '' }}>Rini</option>
                        <option value="Kaji Sun" {{ $item->nama_pengepul == 'Kaji Sun' ? 'selected' : '' }}>Kaji Sun</option>
                        <option value="Kaji Tino" {{ $item->nama_pengepul == 'Kaji Tino' ? 'selected' : '' }}>Kaji Tino</option>
                        <option value="Tri" {{ $item->nama_pengepul == 'Tri' ? 'selected' : '' }}>Tri</option>
                        <option value="Pii" {{ $item->nama_pengepul == 'Pii' ? 'selected' : '' }}>Pii</option>
                        <option value="Agus" {{ $item->nama_pengepul == 'Agus' ? 'selected' : '' }}>Agus</option>
                        <option value="Tilah Prawi" {{ $item->nama_pengepul == 'Tilah Prawi' ? 'selected' : '' }}>Tilah Prawi</option>
                    </select>
                    
                    <select name="hasil_laut[{{ $index }}][status_pembayaran]" class="form-control form-control-sm input-struk mr-2 border-0" style="flex: 1;" required>
                        <option value="Lunas" {{ $item->status_pembayaran == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ $item->status_pembayaran == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>

                    <button type="button" class="btn btn-sm text-danger font-weight-bold p-1" onclick="hapusBaris('baris-ikan-{{ $index }}')">
                        <i class="bi bi-trash3-fill" style="font-size: 16px;"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-input-ikan mr-2" style="flex: 1;">
                        <input type="text" list="daftar-ikan" name="hasil_laut[{{ $index }}][jenis]" class="form-control form-control-sm font-weight-bold" value="{{ $item->jenis_hasil_laut }}" placeholder="Pilih/Ketik Ikan" required autocomplete="off">
                    </div>
                    
                    <div class="input-group input-group-sm" style="flex: 1.2;">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 bg-transparent text-muted small">Rp</span>
                        </div>
                        <input type="number" name="hasil_laut[{{ $index }}][harga]" class="form-control input-struk text-right text-info nilai-harga" value="{{ intval($item->harga) }}" oninput="hitungTotalBaru()" required>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" id="btn-tambah-baris" class="btn btn-outline-info btn-block font-weight-bold mb-4 py-2" style="border-radius: 10px; border-style: dashed;">
            <i class="bi bi-plus-circle mr-1"></i> Tambah Ikan Lainnya
        </button>

        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; background-color: #f8fbfa;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small font-weight-bold">Total Kotor:</span>
                    <strong class="text-secondary" id="teks-total-kotor">Rp 0</strong>
                </div>

                <div class="form-group mb-3 pb-3 border-bottom border-secondary">
                    <label class="text-muted small font-weight-bold">Biaya Admin</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 bg-transparent text-muted small">Rp</span>
                        </div>
                        <input type="number" name="biaya_admin" id="input-biaya-admin" class="form-control input-struk text-right text-danger" value="{{ intval($penjualan->biaya_admin) }}" oninput="hitungTotalBaru()" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success font-weight-bold" style="letter-spacing: 0.5px;">TOTAL AKHIR</span>
                    <strong class="text-success" style="font-size: 24px;" id="teks-total-akhir">Rp 0</strong>
                </div>
            </div>
        </div>
        <div class="btn-bawah-ganda">
            <a href="{{ route('home') }}" class="btn btn-light text-black">Batal</a>
            <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-black">
                <i class="bi bi-floppy-fill mr-1"></i> Simpan Edit
            </button>
        </div>
        <datalist id="daftar-ikan">
            <option value="Tongkol"></option>
            <option value="Tenggiri"></option>
            <option value="Kerapu"></option>
            <option value="Bawal"></option>
            <option value="Kakap Merah"></option>
            <option value="Kembung"></option>
            <option value="Rajungan"></option>
            <option value="Cumi-cumi"></option>
            <option value="Udang"></option>
            </datalist>
    </form>
</div>

<script>
    let urutanKe = {{ count($penjualan->detail) }}; 

    document.getElementById('btn-tambah-baris').addEventListener('click', function() {
        let idBaris = 'baris-ikan-' + urutanKe;
        
        let kotakBaru = `
            <div class="mb-3 p-3 card-ikan" id="${idBaris}">
                <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-2">
                    <select name="hasil_laut[${urutanKe}][pengepul]" class="form-control form-control-sm input-struk mr-2 border-0 bg-light w-50" required>
                        <option value="">- Pilih Pengepul -</option>
                        <option value="Kaji Arip">Kaji Arip</option>
                        <option value="BBI">BBI</option>
                        <option value="Tarom">Tarom</option>
                        <option value="Pramono">Pramono</option>
                        <option value="TPI Banyutowo">TPI Banyutowo</option>
                        <option value="Rossa">Rossa</option>
                        <option value="Rini">Rini</option>
                        <option value="Kaji Sun">Kaji Sun</option>
                        <option value="Kaji Tino">Kaji Tino</option>
                        <option value="Tri">Tri</option>
                        <option value="Pii">Pii</option>
                        <option value="Agus">Agus</option>
                        <option value="Tilah Prawi">Tilah Prawi</option>
                    </select>
                    
                    <select name="hasil_laut[${urutanKe}][status_pembayaran]" class="form-control form-control-sm input-struk mr-2 border-0" style="flex: 1;" required>
                        <option value="Lunas">Lunas</option>
                        <option value="Belum Lunas">Belum Lunas</option>
                    </select>

                    <button type="button" class="btn btn-sm text-danger font-weight-bold p-1" onclick="hapusBaris('${idBaris}')">
                        <i class="bi bi-trash3-fill" style="font-size: 16px;"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-input-ikan mr-2" style="flex: 1;">
                        <input type="text" list="daftar-ikan" name="hasil_laut[${urutanKe}][jenis]" class="form-control form-control-sm font-weight-bold" placeholder="Pilih/Ketik Ikan" required autocomplete="off">
                    </div>
                    
                    <div class="input-group input-group-sm" style="flex: 1.2;">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-0 bg-transparent text-muted small">Rp</span>
                        </div>
                        <input type="number" name="hasil_laut[${urutanKe}][harga]" class="form-control input-struk text-right text-info nilai-harga" placeholder="Harga" oninput="hitungTotalBaru()" required>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('area-hasil-laut').insertAdjacentHTML('beforeend', kotakBaru);
        urutanKe++; 
    });

    function hapusBaris(id) {
        let elemen = document.getElementById(id);
        if(elemen) {
            elemen.remove();
            hitungTotalBaru(); 
        }
    }

    // FUNGSI JAVASCRIPT DIPERBARUI UNTUK MENGHITUNG ADMIN
    function hitungTotalBaru() {
        let totalKotor = 0;
        let semuaHarga = document.querySelectorAll('.nilai-harga');
        
        // 1. Hitung total semua ikan
        semuaHarga.forEach(function(inputBox) {
            let angka = parseInt(inputBox.value);
            if (!isNaN(angka)) {
                totalKotor += angka;
            }
        });

        // 2. Ambil nilai biaya admin
        let biayaAdmin = parseInt(document.getElementById('input-biaya-admin').value);
        if (isNaN(biayaAdmin)) {
            biayaAdmin = 0;
        }

        // 3. Hitung total akhir
        let totalAkhir = totalKotor - biayaAdmin;

        // 4. Tampilkan ke layar
        document.getElementById('teks-total-kotor').innerText = 'Rp ' + totalKotor.toLocaleString('id-ID');
        document.getElementById('teks-total-akhir').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');
    }

    window.onload = function() {
        hitungTotalBaru();
    };
</script>

@endsection