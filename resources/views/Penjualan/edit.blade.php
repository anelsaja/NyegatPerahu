@extends('layouts.app')
@section('content')

<style>
    /* Sembunyikan navigasi bawah KHUSUS di halaman ini */
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 120px !important; }

    .info-table { width: 100%; margin-bottom: 15px; font-size: 13px; }
    .info-table td { padding: 4px 0; border-bottom: 1px dashed #eee; }
    .info-table td:last-child { text-align: right; font-weight: bold; }
    
    /* Bar putih di bawah */
    .btn-bawah-ganda { 
        position: fixed; bottom: 0; left: 0; width: 100%; 
        background-color: #ffffff; padding: 15px; 
        border-top: 1px solid #f0f0f0; z-index: 1050; 
        display: flex; justify-content: center; gap: 15px;
    }

    .btn-bawah-ganda a, .btn-bawah-ganda button {
        flex: 1; max-width: 240px; padding: 14px;
        border-radius: 12px; font-weight: bold; text-align: center;
        border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: none; 
    }
    .btn-bawah-ganda a:active, .btn-bawah-ganda button:active {
        transform: translateY(2px); box-shadow: 0 2px 6px rgba(0,0,0,0.1); 
    }

    /* Modifikasi Input agar terlihat seperti struk */
    .input-struk {
        border: 1px solid #e0e0e0; background-color: #fafafa;
        border-radius: 8px; font-size: 14px; font-weight: bold;
    }
    .input-struk:focus { background-color: #fff; border-color: #5bc0de; box-shadow: none; }
    
    /* Kotak Ikan */
    .card-ikan {
        background-color: #ffffff; border-radius: 12px; 
        border: 1px solid #eaeaea; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
</style>

<div class="p-3">
    <div class="text-center mb-4 mt-2">
        <div style="width: 60px; height: 60px; background-color: #fff3cd; color: #ffc107; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; font-size: 26px; margin-bottom: 10px;">
            <i class="bi bi-pencil-square"></i>
        </div>
        <h5 class="font-weight-bold mb-1 text-dark">Edit Transaksi</h5>
        <p class="text-muted small">Perbarui data atau ubah status pembayaran</p>
    </div>

    <form action="{{ route('penjualan.update', $penjualan->penjualan_id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="tanggal" value="{{ $penjualan->tanggal }}">
        <input type="hidden" name="nelayan_id" value="{{ $penjualan->nelayan_id }}">

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #f8f9fa;">
            <div class="card-body p-3">
                <label class="font-weight-bold text-muted small mb-2">Status Pembayaran</label>
                <select name="status_pembayaran" class="form-control form-control-lg border-0 shadow-sm" style="border-radius: 10px; font-weight: bold;">
                    <option value="Lunas" {{ $penjualan->status_pembayaran == 'Lunas' ? 'selected' : '' }}>✅ Lunas (Uang Diterima)</option>
                    <option value="Belum Lunas" {{ $penjualan->status_pembayaran == 'Belum Lunas' ? 'selected' : '' }}>⏳ Belum Lunas (Masih Hutang)</option>
                </select>
            </div>
        </div>

        <table class="info-table mb-4">
            <tr><td class="text-muted">Tanggal</td><td>{{ date('d M Y', strtotime($penjualan->tanggal)) }}</td></tr>
            <tr><td class="text-muted">Nama Nelayan</td><td>{{ $penjualan->nelayan->nama ?? '-' }}</td></tr>
            <tr><td class="text-muted">Dicatat Oleh</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <h6 class="font-weight-bold mt-4 mb-3 pb-2 border-bottom">Rincian Tangkapan</h6>

        <div id="area-hasil-laut">
            @foreach($penjualan->detail as $index => $item)
            <div class="mb-3 p-3 card-ikan" id="baris-ikan-{{ $index }}">
                
                <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-2">
                    <select name="hasil_laut[{{ $index }}][pengepul]" class="form-control form-control-sm input-struk w-75 border-0 bg-light" required>
                        <option value="Kaji Arip" {{ $item->nama_pengepul == 'Kaji Arip' ? 'selected' : '' }}>Pengepul: Kaji Arip</option>
                        <option value="BBI" {{ $item->nama_pengepul == 'BBI' ? 'selected' : '' }}>Pengepul: BBI</option>
                        <option value="Tarom" {{ $item->nama_pengepul == 'Tarom' ? 'selected' : '' }}>Pengepul: Tarom</option>
                        <option value="Panggang" {{ $item->nama_pengepul == 'Panggang' ? 'selected' : '' }}>Pengepul: Panggang</option>
                    </select>
                    <button type="button" class="btn btn-sm text-danger font-weight-bold" onclick="hapusBaris('baris-ikan-{{ $index }}')">
                        <i class="bi bi-trash3-fill" style="font-size: 16px;"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" name="hasil_laut[{{ $index }}][jenis]" class="form-control form-control-sm input-struk mr-2" style="flex: 1;" value="{{ $item->jenis_hasil_laut }}" required>
                    
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

        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; background-color: #eaf6fd;">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <span class="font-weight-bold text-muted small">Total Keseluruhan:</span>
                <strong class="text-info" style="font-size: 22px;" id="teks-total-semua">Rp 0</strong>
            </div>
        </div>

        <div class="btn-bawah-ganda">
            <a href="{{ route('home') }}" class="btn btn-light font-weight-bold shadow-sm text-secondary" style="border-radius: 12px;">
                Batal
            </a>
            <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-white" style="border-radius: 12px;">
                <i class="bi bi-floppy-fill mr-1"></i> Simpan Edit
            </button>
        </div>
    </form>
</div>

<script>
    let urutanKe = {{ count($penjualan->detail) }}; 

    document.getElementById('btn-tambah-baris').addEventListener('click', function() {
        let idBaris = 'baris-ikan-' + urutanKe;
        
        // 3. KONSISTENSI ICON (Ubah fa-trash-alt jadi bi-trash3-fill)
        let kotakBaru = `
            <div class="mb-3 p-3 card-ikan" id="${idBaris}">
                <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-2">
                    <select name="hasil_laut[${urutanKe}][pengepul]" class="form-control form-control-sm input-struk w-75 border-0 bg-light" required>
                        <option value="">-- Pilih Pengepul --</option>
                        <option value="Kaji Arip">Pengepul: Kaji Arip</option>
                        <option value="BBI">Pengepul: BBI</option>
                        <option value="Tarom">Pengepul: Tarom</option>
                        <option value="Panggang">Pengepul: Panggang</option>
                    </select>
                    <button type="button" class="btn btn-sm text-danger font-weight-bold" onclick="hapusBaris('${idBaris}')">
                        <i class="bi bi-trash3-fill" style="font-size: 16px;"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <input type="text" name="hasil_laut[${urutanKe}][jenis]" class="form-control form-control-sm input-struk mr-2" style="flex: 1;" placeholder="Jenis Ikan" required>
                    
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
            hitungTotalBaru(); // Hitung ulang total jika ada baris yang dihapus
        }
    }

    // FUNGSI BARU: Kalkulator Total Otomatis
    function hitungTotalBaru() {
        let total = 0;
        let semuaHarga = document.querySelectorAll('.nilai-harga');
        
        semuaHarga.forEach(function(inputBox) {
            let angka = parseInt(inputBox.value);
            if (!isNaN(angka)) {
                total += angka;
            }
        });

        // Tampilkan ke layar dengan format Rupiah
        document.getElementById('teks-total-semua').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Jalankan satu kali saat halaman edit baru saja dibuka
    window.onload = function() {
        hitungTotalBaru();
    };
</script>

@endsection