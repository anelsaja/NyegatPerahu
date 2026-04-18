@extends('layouts.app')
@section('content')

<style>
    /* Sembunyikan navigasi bawah KHUSUS di halaman ini */
    .bottom-nav { display: none !important; }
    .mobile-container { padding-bottom: 100px !important; }

    .info-table { width: 100%; margin-bottom: 15px; font-size: 13px; }
    .info-table td { padding: 4px 0; border-bottom: 1px dashed #eee; }
    .info-table td:last-child { text-align: right; font-weight: bold; }
    
/* Bar putih di bawah: Melebar penuh ke ujung browser */
    .btn-bawah-ganda { 
        position: fixed; 
        bottom: 0; 
        left: 0; 
        width: 100%; 
        background-color: #ffffff; 
        padding: 15px; 
        border-top: 1px solid #f0f0f0; 
        z-index: 1050; 
        display: flex; 
        justify-content: center; /* Menjaga tombol tetap di tengah pada layar PC */
        gap: 15px;
    }

    /* Desain Tombol: Fokus pada efek Drop Shadow (Tanpa Hover) */
    .btn-bawah-ganda a, .btn-bawah-ganda button {
        flex: 1; 
        max-width: 240px; /* Batas lebar tombol di PC agar tetap rapi */
        padding: 14px;
        border-radius: 12px;
        font-weight: bold;
        text-align: center;
        border: none; /* Bersihkan border agar shadow lebih tegas */
        
        /* Efek Drop Shadow agar terlihat timbul/clickable */
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
        
        /* Pastikan tidak ada perubahan warna/shadow saat di-hover */
        transition: none; 
    }

    /* Menghilangkan dekorasi default browser saat hover */
    .btn-bawah-ganda a:hover, .btn-bawah-ganda button:hover {
        text-decoration: none;
        color: inherit; /* Warna teks tidak berubah */
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); /* Shadow tetap sama */
        transform: none; /* Tidak bergeser saat hover */
    }

    /* Efek Klik (Active): Hanya aktif saat ditekan jempol/mouse */
    .btn-bawah-ganda a:active, .btn-bawah-ganda button:active {
        transform: translateY(2px); /* Tombol seolah tertekan ke bawah */
        box-shadow: 0 2px 6px rgba(0,0,0,0.1); /* Shadow mengecil saat ditekan */
    }

    /* Modifikasi Input agar terlihat menyatu dengan layar putih (Struk) */
    .input-struk {
        border: 1px solid #e0e0e0;
        background-color: #fafafa;
        border-radius: 8px;
        font-size: 13px;
        font-weight: bold;
    }
    .input-struk:focus { background-color: #fff; border-color: #5bc0de; box-shadow: none; }
</style>

<div class="p-3">
    <div class="text-center mb-4 mt-2">
        <h5 class="font-weight-bold mb-1 text-warning">Edit Rincian Transaksi</h5>
        <span class="badge {{ $penjualan->status_pembayaran == 'Lunas' ? 'badge-success' : 'badge-warning' }}">{{ $penjualan->status_pembayaran }}</span>
    </div>

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

        <h6 class="font-weight-bold mt-4 mb-3 pb-2 border-bottom">Edit Tangkapan per Pengepul</h6>

        <div id="area-hasil-laut">
            @foreach($penjualan->detail as $index => $item)
            <div class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 12px; border: 1px solid #eee;" id="baris-ikan-{{ $index }}">
                
                <div class="d-flex justify-content-between mb-2 align-items-center">
                    <select name="hasil_laut[{{ $index }}][pengepul]" class="form-control form-control-sm input-struk w-75" required>
                        <option value="Kaji Arip" {{ $item->nama_pengepul == 'Kaji Arip' ? 'selected' : '' }}>Pengepul: Kaji Arip</option>
                        <option value="BBI" {{ $item->nama_pengepul == 'BBI' ? 'selected' : '' }}>Pengepul: BBI</option>
                        <option value="Tarom" {{ $item->nama_pengepul == 'Tarom' ? 'selected' : '' }}>Pengepul: Tarom</option>
                        <option value="Panggang" {{ $item->nama_pengepul == 'Panggang' ? 'selected' : '' }}>Pengepul: Panggang</option>
                    </select>
                    <button type="button" class="btn btn-sm text-danger font-weight-bold" onclick="hapusBaris('baris-ikan-{{ $index }}')">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between">
                    <input type="text" name="hasil_laut[{{ $index }}][jenis]" class="form-control form-control-sm input-struk mr-2 w-50" value="{{ $item->jenis_hasil_laut }}" required>
                    <input type="number" name="hasil_laut[{{ $index }}][harga]" class="form-control form-control-sm input-struk text-right text-info" value="{{ intval($item->harga) }}" required>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" id="btn-tambah-baris" class="btn btn-outline-info btn-sm btn-block font-weight-bold mb-4" style="border-radius: 8px; border-style: dashed;">
            + Tambah Ikan Lainnya
        </button>

        <div class="btn-bawah-ganda">
            <a href="{{ route('home') }}" class="btn btn-light font-weight-bold shadow-sm" style="border-radius: 10px; border: 1px solid #ccc;">
                Batal
            </a>
            <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-white" style="border-radius: 10px;">
                <i class="bi bi-floppy-fill"></i> Simpan Edit
            </button>
        </div>
    </form>
</div>

<script>
    // Mulai indeks dari jumlah ikan yang sudah ada di database
    let urutanKe = {{ count($penjualan->detail) }}; 

    document.getElementById('btn-tambah-baris').addEventListener('click', function() {
        let idBaris = 'baris-ikan-' + urutanKe;
        let kotakBaru = `
            <div class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 12px; border: 1px solid #eee;" id="${idBaris}">
                <div class="d-flex justify-content-between mb-2 align-items-center">
                    <select name="hasil_laut[${urutanKe}][pengepul]" class="form-control form-control-sm input-struk w-75" required>
                        <option value="">-- Pilih Pengepul --</option>
                        <option value="Kaji Arip">Pengepul: Kaji Arip</option>
                        <option value="BBI">Pengepul: BBI</option>
                        <option value="Tarom">Pengepul: Tarom</option>
                        <option value="Panggang">Pengepul: Panggang</option>
                    </select>
                    <button type="button" class="btn btn-sm text-danger font-weight-bold" onclick="hapusBaris('${idBaris}')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between">
                    <input type="text" name="hasil_laut[${urutanKe}][jenis]" class="form-control form-control-sm input-struk mr-2 w-50" placeholder="Jenis Ikan" required>
                    <input type="number" name="hasil_laut[${urutanKe}][harga]" class="form-control form-control-sm input-struk text-right text-info" placeholder="Harga (Rp)" required>
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
        }
    }
</script>

@endsection