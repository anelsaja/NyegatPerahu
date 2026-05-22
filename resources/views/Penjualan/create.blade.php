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
    
    /* 2. DESAIN TOMBOL KOTAK (NELAYAN & PENGEPUL) */
    .grid-btn {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .btn-kotak { 
        background-color: #e0e0e0;
        border-radius: 15px;
        padding: 15px 10px; 
        text-align: center;
        font-weight: bold;
    }

    .btn-kotak:active {
        transform: scale(0.95);
    }

    .icon-box {
        width: 50px;
        height: 50px;
        border: 3px solid #333;
        border-radius: 15px;
        margin: 0 auto 10px auto;
        font-size: 24px;
    }

    .icon-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    }
    
    /* Input harga disembunyikan di awal */
    .input-harga {
        display: none;
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 15px;
        margin-top: 10px;
        text-align: left;
        font-weight: bold;
    }
    
    /* Container hanya untuk posisi */
    .btn-bawah { 
        position: fixed;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 95%;
        z-index: 999;
    }

    /* Tombol full clickable */
    .btn-kembali {
        display: block;
        width: 100%;
        border-radius: 15px;
        padding: 18px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        text-decoration: none !important;
        border: none;
        background-color: whitesmoke;
        color: black;
    }

    /* Desain Khusus Kartu Tambah Nelayan */
    .btn-tambah-baru {
        background-color: #f4fbff !important;
        border: 2px dashed #5bc0de !important;
        color: #17a2b8 !important;
    }

    .icon-box-tambah {
        font-size: 28px;
        color: #5bc0de;
        margin-bottom: 8px;
        display: flex;
        justify-content: center;
    }
    
    .btn-status {
        flex: 1;
        padding: 14px;
        border-radius: 15px;
        text-align: center;
        font-weight: bold;
        background-color: #f1f1f1;
    }

    /* Tombol aktif lunas */
    .btn-status.lunas-aktif {
        background-color: #d4edda;
        color: #155724;
        border-color: #28a745;
    }

    /* Tombol aktif belum lunas */
    .btn-status.belum-aktif {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #dc3545;
    }

    /* 3. ANIMASI PINDAH HALAMAN */
    .step-section {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }

    .step-section.active {
        display: block;
    } /* Hanya yang punya class 'active' yang tampil */

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="p-3">
    <div id="step-1" class="step-section active">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <p class="font-weight-bold">Pilih Nelayan</p>

        <div class="grid-btn">
            @foreach($nelayans as $n)
            <div class="btn-kotak" onclick="pilihNelayan({{ $n->nelayan_id }}, `{{ $n->nama }}`)">
                
                <div class="icon-box" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    @if($n->foto_profil)
                        <img src="{{ asset('images/nelayan/' . $n->foto_profil) }}" alt="Foto {{ $n->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 20px;">👤</span>
                    @endif
                </div>
                {{ $n->nama }}
            </div>
            @endforeach
        </div>
        <div class="btn-kotak btn-tambah-baru shadow-sm" onclick="window.location.href='{{ route('nelayan.create', ['asal' => 'penjualan']) }}'">
            <div class="icon-box-tambah">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <span class="font-weight-bold">Tambah Nelayan Baru</span>
        </div>
        <div style="height: 120px;"></div>

        <div class="btn-bawah">
            <a href="{{ route('home') }}" class="btn-kembali" style="background-color: red; color: white;">
                <i class="bi bi-x-circle"></i>
                <span>Batal</span>
            </a>
        </div>
    </div>

    <div id="step-2" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td id="info-nelayan-nama">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <a href="javascript:void(0)" onclick="pindahKeStep(1)" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali Pilih Nelayan
        </a>

        <div style="height: 20px;"></div>
        <p class="font-weight-bold">Pilih Pengepul</p>
        <div class="grid-btn">
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Arip')">Kaji Arip</div>
            <div class="btn-kotak" onclick="pilihPengepul('BBI')">BBI</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tarom')">Tarom</div>
            <div class="btn-kotak" onclick="pilihPengepul('Pramono')">Pramono</div>
            <div class="btn-kotak" onclick="pilihPengepul('TPI Banyutowo')">TPI Banyutowo</div>
            <div class="btn-kotak" onclick="pilihPengepul('Rossa')">Rossa</div>
            <div class="btn-kotak" onclick="pilihPengepul('Rini')">Rini</div>
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Sun')">Kaji Sun</div>
            <div class="btn-kotak" onclick="pilihPengepul('kaji Tino')">Kaji Tino</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tri')">Tri</div>
            <div class="btn-kotak" onclick="pilihPengepul('Pi`i')">Pi`i</div>
            <div class="btn-kotak" onclick="pilihPengepul('Agus')">Agus</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tilah Prawi')">Tilah Prawi</div>
        </div>
        <div class="btn-kotak btn-tambah-baru shadow-sm mt-3" data-toggle="modal" data-target="#modalPengepulBaru">
            <div class="icon-box-tambah">
                <i class="bi bi-shop"></i>
            </div>
            <span class="font-weight-bold">Tambah Pengepul Baru</span>
        </div>
        <div style="height: 70px;"></div>
    </div>

    <div id="step-3" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <div id="alert-peringatan-ikan" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill text-danger mr-2" style="font-size: 22px;"></i>
                <span style="font-size: 14px;">Silahkan pilih minimal 1 ikan dan masukkan harganya</span>
            </div>
        </div>

        <a href="javascript:void(0)" onclick="pindahKeStep(2)" class="btn-kembali" style="background-color: whitesmoke; color: black;">
            <i class="bi bi-arrow-left"></i> Kembali Pilih Pengepul
        </a>

        <div style="height: 20px;"></div>
        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Pengepul: <span id="info-pengepul-nama" class="text-info">-</span></h6>
        <p class="font-weight-bold">Pilih Jenis Nama Hasil Laut</p>
        <div class="grid-btn" id="container-ikan">
            <div class="btn-kotak" onclick="toggleInputIkan('Timbangan')">
                Timbangan
                <input type="number" id="input-Timbangan" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Tebleng')">
                Tebleng
                <input type="number" id="input-Tebleng" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Kembung')">
                Kembung
                <input type="number" id="input-Kembung" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Cucut')">
                Cucut
                <input type="number" id="input-Cucut" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Pirek')">
                Pirek
                <input type="number" id="input-Pirek" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Lelang')">
                Lelang
                <input type="number" id="input-Lelang" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Blekutak')">
                Blekutak
                <input type="number" id="input-Blekutak" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Rajungan')">
                Rajungan
                <input type="number" id="input-Rajungan" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Windu')">
                Windu
                <input type="number" id="input-Windu" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Elek-Elekan')">
                Elek-Elekan
                <input type="number" id="input-Elek-Elekan" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
        </div>
        <div class="btn-kotak btn-tambah-baru shadow-sm mt-3 mb-4" data-toggle="modal" data-target="#modalIkanBaru">
            <div class="icon-box-tambah">
                <i class="bi bi-plus-circle"></i> </div>
            <span class="font-weight-bold">Tambah Jenis Ikan Baru</span>
        </div>

        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <label class="font-weight-bold text-muted small d-block mb-3">Status Pembayaran</label>
                <div class="d-flex" style="gap: 10px;">
                    <!-- Tombol Lunas -->
                    <div id="btn-status-lunas"
                        class="btn-status lunas-aktif"
                        onclick="pilihStatus('Lunas')">
                        Lunas
                    </div>
                    <!-- Tombol Belum Lunas -->
                    <div id="btn-status-belum"
                        class="btn-status"
                        onclick="pilihStatus('Belum Lunas')">
                        Belum Lunas
                    </div>
                </div>
                <!-- input hidden -->
                <input type="hidden" id="pilihan-status" value="Lunas">
            </div>
        </div>

        <div style="height: 120px;"></div>
        <div class="btn-bawah">
            <button onclick="simpanKeKeranjang()" class="btn-kembali" style="background-color: green; color: white;">
            + Tambahkan Data Ini
            </button>
        </div>
    </div>

    <div id="step-4" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <div id="area-keranjang-belanja"></div>

        <div class="card p-3 mb-3 bg-light">
            <div class="d-flex justify-content-between">
                <span>Total Sementara</span>
                <strong id="total-semua">Rp 0</strong>
            </div>
        </div>

        <h6 class="font-weight-bold pt-3 border-top mb-3">Pilih Pengepul Lainnya</h6>
        <div class="grid-btn">
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Arip')">Kaji Arip</div>
            <div class="btn-kotak" onclick="pilihPengepul('BBI')">BBI</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tarom')">Tarom</div>
            <div class="btn-kotak" onclick="pilihPengepul('Pramono')">Pramono</div>
            <div class="btn-kotak" onclick="pilihPengepul('TPI Banyutowo')">TPI Banyutowo</div>
            <div class="btn-kotak" onclick="pilihPengepul('Rossa')">Rossa</div>
            <div class="btn-kotak" onclick="pilihPengepul('Rini')">Rini</div>
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Sun')">Kaji Sun</div>
            <div class="btn-kotak" onclick="pilihPengepul('kaji Tino')">Kaji Tino</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tri')">Tri</div>
            <div class="btn-kotak" onclick="pilihPengepul('Pi`i')">Pi`i</div>
            <div class="btn-kotak" onclick="pilihPengepul('Agus')">Agus</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tilah Prawi')">Tilah Prawi</div>
        </div>

        <div style="height: 100px;"></div>
        <div class="btn-bawah">
            <button onclick="pindahKeStep(5)" class="btn-kembali" style="background-color: green; color: white;">
                Lanjut ke biaya admin
            </button>
        </div>
    </div>

    <div id="step-5" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <div class="card p-3 shadow-sm mb-4">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Total Tangkapan</span>
                <strong id="teks-total-kotor-step5">Rp 0</strong>
            </div>
            <div class="form-group border-bottom pb-3">
                <label>Biaya Admin</label>
                <input type="number" id="input-admin" class="form-control text-left" placeholder="Rp" oninput="hitungTotalAkhir()">
            </div>
            <div class="d-flex justify-content-between mt-3">
                <span class="font-weight-bold text-success">TOTAL AKHIR</span>
                <strong id="teks-total-akhir" class="text-success" style="font-size: 24px;">Rp 0</strong>
            </div>
        </div>

        <div class="d-flex mb-4" style="gap: 10px;">
            <button type="button" onclick="kirimKeDatabaseLaravel('cetak')" class="btn-kembali" style="flex: 1; background-color: blue; color: white;">
                <i class="bi bi-printer-fill mr-1"></i> Cetak Karcis
            </button>
        </div>
    </div>
</div>

<form id="form-rahasia" action="{{ route('penjualan.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
    <input type="hidden" name="nelayan_id" id="input-rahasia-nelayan">
    <input type="hidden" name="aksi_transaksi" id="input-aksi-transaksi">
    <input type="hidden" name="biaya_admin" id="input-admin-hidden">
    <div id="tempat-input-ikan-rahasia"></div>
</form>

<div class="modal fade" id="modalPengepulBaru" tabindex="-1" aria-labelledby="modalPengepulLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">  
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="modalPengepulLabel">Tambah Pengepul Baru</h5>
            </div>

            <div class="modal-body pt-3">
                <div id="alert-pengepul-kosong" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 5px solid #dc3545; padding: 10px 15px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 18px;"></i>
                        <span class="font-weight-bold text-dark" style="font-size: 13px;">Nama pengepul tidak boleh kosong!</span>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="text-muted small font-weight-bold">Nama Pengepul</label>
                    <input type="text" id="inputPengepulBaru" class="form-control shadow-sm" style="border-radius: 15px; border: 2px solid #eaf6fd; background-color: #f8fcff;" placeholder="Masukkan nama pengepul">
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light shadow-sm font-weight-bold" data-dismiss="modal" style="border-radius: 15px; padding: 10px 15px;">Batal</button>
                <button type="button" class="btn btn-success shadow-sm font-weight-bold px-4" style="border-radius: 15px; background-color: #08a10b; padding: 10px 15px;" onclick="simpanPengepulBaru()">
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalIkanBaru" tabindex="-1" aria-labelledby="modalIkanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="modalIkanLabel">Tambah Jenis Ikan Baru</h5>
            </div>
          
            <div class="modal-body pt-3">
                <div id="alert-ikan-kosong" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 5px solid #dc3545; padding: 10px 15px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 18px;"></i>
                        <span class="font-weight-bold text-dark" style="font-size: 13px;">Nama ikan tidak boleh kosong!</span>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="text-muted small font-weight-bold">Nama Jenis Ikan</label>
                    <input type="text" id="inputIkanBaru" class="form-control shadow-sm" style="border-radius: 15px; border: 2px solid #eaf6fd; background-color: #f8fcff;" placeholder="Masukkan nama jenis ikan">
                </div>
            </div>
          
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light shadow-sm font-weight-bold" data-dismiss="modal" style="border-radius: 15px; padding: 10px 15px;">Batal</button>
                <button type="button" class="btn btn-success shadow-sm font-weight-bold px-4" style="border-radius: 15px; background-color: #08a10b; padding: 10px 15px;" onclick="simpanIkanBaru()">
                    Tambah
                </button>
            </div> 
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapusPengepul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-danger">Hapus Transaksi</h5>
            </div>

            <div class="modal-body pt-3">
                <div class="alert shadow-sm mb-3" style="border-radius: 15px; background-color: #fde8ec; border-left: 5px solid #dc3545; padding: 10px 15px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 18px;"></i>
                        <span class="font-weight-bold text-dark" style="font-size: 13px;">
                            Yakin ingin menghapus transaksi pengepul <b id="namaPengepulHapus"></b> ?
                        </span>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light shadow-sm font-weight-bold" data-dismiss="modal" style="border-radius: 15px; padding: 10px 15px;">Batal</button>
                <button type="button" class="btn btn-danger shadow-sm font-weight-bold px-4" style="border-radius: 15px; padding: 10px 15px;" onclick="konfirmasiHapusPengepul()">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
// 1. MEMORI APLIKASI
// Ini seperti buku catatan sementara sebelum data dikirim ke Database
let memori = {
    nelayan_id: null,
    pengepul_aktif: '',
    daftar_belanja: [] // Kosong pada awalnya
};
let totalSemuaGlobal = 0;

// 2. FUNGSI PINDAH HALAMAN
// Tugasnya: Menyembunyikan semua step, lalu menampilkan step yang diminta
function pindahKeStep(nomor) {
    // Sembunyikan semua step
    document.querySelectorAll('.step-section').forEach(s => s.style.display = 'none');
        
    // Tampilkan step yang dipilih
    let stepSekarang = document.getElementById('step-' + nomor);
    if (stepSekarang) {
        stepSekarang.style.display = 'block';
    }

    // KHUSUS: Jika pindah ke Step 5, sinkronkan angkanya
    if (nomor === 5) {
        hitungTotalAkhir();
    }
}

// 3. STEP 1 (Pilih Nelayan)
function pilihNelayan(id, nama) {
    // Catat di memori
    memori.nelayan_id = id;
        
    // Ubah teks '-' di tabel menjadi nama nelayan yang dipilih
    document.getElementById('info-nelayan-nama').innerText = nama;
    document.querySelectorAll('.info-nelayan-nama-teks').forEach(function(teks) {
        teks.innerText = nama;
    });
        
    // Lanjut ke Step 2
    pindahKeStep(2);
}

// 4. STEP 2 (Pilih Pengepul)
function pilihPengepul(namaPengepul) {
    // Catat di memori
    memori.pengepul_aktif = namaPengepul;
        
    // Tampilkan nama pengepul di judul Step 3
    document.getElementById('info-pengepul-nama').innerText = namaPengepul;
        
    // Lanjut ke Step 3
    pindahKeStep(3);
}

// 5. STEP 2 (Tambah Pengepul Baru)
function simpanPengepulBaru() {
    // 1. Ambil nilai yang diketik dari kolom input
    let inputElement = document.getElementById('inputPengepulBaru');
    let namaBaru = inputElement.value.trim();
    let alertKosong = document.getElementById('alert-pengepul-kosong');

    // 2. Cek apakah tidak kosong
    if (namaBaru !== "") {
        // 1. Sembunyikan alert jika sebelumnya sempat muncul
        alertKosong.style.display = 'none';
            
        // Tutup pop-up modal secara otomatis
        $('#modalPengepulBaru').modal('hide');
            
        // Kosongkan kembali kolom input (jika pengguna kembali ke step 2 nanti)
        inputElement.value = "";
            
        // Lemparkan namanya ke fungsi pilihPengepul (Otomatis masuk ke Step 3)
        pilihPengepul(namaBaru);
    } else {       
        // 1. Munculkan peringatan merah di dalam pop-up
        alertKosong.style.display = 'block';
            
        // 2. Fokuskan kursor kembali ke kolom ketikan
        inputElement.focus();
            
        // 3. (Opsional) Hilangkan peringatan secara otomatis setelah 3 detik
        setTimeout(function() {
            alertKosong.style.display = 'none';
        }, 3000);
    }
}

// 6. STEP 3 (Pilih Kotak Ikan)
// Tugasnya: Memunculkan atau menyembunyikan kolom input harga (Rp)
function toggleInputIkan(namaIkan) {
    let kotakInput = document.getElementById('input-' + namaIkan);
        
    if (kotakInput.style.display === 'block') {
        kotakInput.style.display = 'none'; // Sembunyikan
        kotakInput.value = ''; // Kosongkan nilainya
    } else {
        kotakInput.style.display = 'block'; // Tampilkan
        kotakInput.focus(); // Langsung aktifkan keyboard HP
    }
}

// 7. STEP 3 (Tambah Jenis Ikan Baru)
function simpanIkanBaru() {
    let inputElement = document.getElementById('inputIkanBaru');
    let namaIkan = inputElement.value.trim();
    let alertKosong = document.getElementById('alert-ikan-kosong'); // Panggil elemen alert

    if (namaIkan !== "") {
        // 1. Sembunyikan alert jika sebelumnya sempat muncul
        alertKosong.style.display = 'none';

        // 2. Tutup Pop-up
        $('#modalIkanBaru').modal('hide');
        inputElement.value = ""; 

        let idAman = namaIkan.replace(/\s+/g, '_');

        let htmlKotakBaru = `
        <div class="btn-kotak" onclick="toggleInputIkan('${idAman}')">
            ${namaIkan} <span class="text-success font-weight-bold">*</span>
            <input type="number" id="input-${idAman}" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
        </div>
        `;

        document.getElementById('container-ikan').insertAdjacentHTML('beforeend', htmlKotakBaru);
    } else {
        // 1. Munculkan peringatan merah di dalam pop-up
        alertKosong.style.display = 'block';
            
        // 2. Fokuskan kursor kembali ke kolom ketikan
        inputElement.focus();
            
        // 3. (Opsional) Hilangkan peringatan secara otomatis setelah 3 detik
        setTimeout(function() {
            alertKosong.style.display = 'none';
        }, 3000);
    }
}

function pilihStatus(status) {

    // Simpan nilai
    document.getElementById('pilihan-status').value = status;

    // Ambil tombol
    let btnLunas = document.getElementById('btn-status-lunas');
    let btnBelum = document.getElementById('btn-status-belum');

    // Reset semua class aktif
    btnLunas.classList.remove('lunas-aktif');
    btnBelum.classList.remove('belum-aktif');

    // Tambahkan class sesuai pilihan
    if (status === 'Lunas') {
        btnLunas.classList.add('lunas-aktif');
    } else {
        btnBelum.classList.add('belum-aktif');
    }
}

// 6. STEP 3 (Tambahkan Data ini)
function simpanKeKeranjang() {
    let adaIkanYangDiisi = false;
    // Ambil status yang dipilih (Lunas / Belum Lunas)
    let statusDipilih = document.getElementById('pilihan-status').value;
        
    // Cek satu-satu semua input harga yang ada di layar
    document.querySelectorAll('.input-harga').forEach(function(kotakInput) {
            
        // Jika kotaknya tampil DAN ada harganya...
        if (kotakInput.style.display === 'block' && kotakInput.value !== '') {
                
            let namaIkan = kotakInput.id.replace('input-', ''); // Ambil nama ikannya
            let hargaIkan = parseInt(kotakInput.value); // Ambil angkanya
                
            // Masukkan data ini ke dalam 'daftar_belanja' di memori
            memori.daftar_belanja.push({
                pengepul: memori.pengepul_aktif,
                jenis: namaIkan,
                harga: hargaIkan,
                status: statusDipilih // Simpan status di sini
            });
                
            adaIkanYangDiisi = true;
                
            // Sembunyikan kembali inputnya untuk transaksi berikutnya
            kotakInput.style.display = 'none';
            kotakInput.value = '';
        }
    });

    if (adaIkanYangDiisi === true) {
        // 1. Sembunyikan alert peringatan jika sebelumnya muncul
        document.getElementById('alert-peringatan-ikan').style.display = 'none';
            
        // 2. Lanjutkan proses seperti biasa
        gambarUlangKeranjangBelanja(); 
        pindahKeStep(4); 
    } else {
        // 1. Tampilkan kotak alert merah di atas
        let alertBox = document.getElementById('alert-peringatan-ikan');
        alertBox.style.display = 'block';
            
        // 2. Gulir layar otomatis ke paling atas agar ibu nelayan pasti melihatnya
        window.scrollTo({ top: 0, behavior: 'smooth' });
            
        // 3. Sembunyikan alert secara otomatis setelah 3.5 detik
        setTimeout(function() {
            // Efek menghilang perlahan (opsional tapi bagus untuk UX)
            alertBox.style.display = 'none';
        }, 3500);
    }
}

// 7. MENGGAMBAR TAMPILAN STRUK DI STEP 4
function gambarUlangKeranjangBelanja() {
    let areaLayar = document.getElementById('area-keranjang-belanja');
    areaLayar.innerHTML = '';

    let lemariPengepul = {};
    totalSemuaGlobal = 0; // reset total

    memori.daftar_belanja.forEach(function(ikan) {
        if (!lemariPengepul[ikan.pengepul]) {
            lemariPengepul[ikan.pengepul] = [];
        }
        lemariPengepul[ikan.pengepul].push(ikan);
    });

    for (let namaPengepul in lemariPengepul) {
        let totalHarga = 0;

        // Ambil status dari ikan pertama dalam grup pengepul ini
        let statusTampil = lemariPengepul[namaPengepul][0].status;
            
        // Tentukan warna badge
        let warnaBadge = (statusTampil === 'Lunas') ? 'badge-success' : 'badge-warning';

        // DESAIN BARU: Menggunakan d-flex agar Tombol Edit, Hapus, & Badge sejajar rapi
        let desainHTML = `
            <div class="mb-4 border-bottom pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">

                    <!-- Kiri: Nama pengepul + status -->
                    <div class="d-flex align-items-center flex-wrap">
                        <h6 class="font-weight-bold mb-0 mr-2">
                            <i class="bi bi-shop"></i> ${namaPengepul}
                        </h6>

                        <span class="badge ${warnaBadge} rounded-pill px-2">
                            ${statusTampil}
                        </span>
                    </div>

                    <!-- Kanan: tombol edit & hapus -->
                    <div>
                        <button class="btn btn-sm btn-warning py-0 px-2 mr-1"
                            style="border-radius: 15px; font-size: 15px; font-weight: bold;"
                            onclick="editPengepul('${namaPengepul}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <button class="btn btn-sm btn-danger py-0 px-2"
                            style="border-radius: 15px; font-size: 15px; font-weight: bold;"
                            onclick="hapusPengepul('${namaPengepul}')">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>

                </div>

                <table class="info-table mt-1">
        `;

        lemariPengepul[namaPengepul].forEach(function(ikan) {
            desainHTML += `<tr><td>${ikan.jenis}</td><td>Rp ${ikan.harga.toLocaleString('id-ID')}</td></tr>`;
            totalHarga += ikan.harga;
        });

        totalSemuaGlobal += totalHarga; // akumulasi total semua

        desainHTML += `
                <tr>
                    <td class="text-right text-muted pt-2 border-top">Sub-total:</td>
                    <td class="pt-2 text-info border-top font-weight-bold" style="font-size: 15px;">
                        Rp ${totalHarga.toLocaleString('id-ID')}
                    </td>
                </tr>
            </table>
        </div>
        `;

        areaLayar.innerHTML += desainHTML;
    }
    // tampilkan total semua
    document.getElementById('total-semua').innerText = "Rp " + totalSemuaGlobal.toLocaleString('id-ID');

    hitungTotalAkhir(); // langsung hitung total akhir
}

function editPengepul(namaPengepul) {
    // 1. Ambil data ikan milik pengepul yang mau diedit
    let dataYangMauDiedit = memori.daftar_belanja.filter(ikan => ikan.pengepul === namaPengepul);

    // 2. HAPUS pengepul ini dari keranjang utama 
    // (Agar saat user menekan 'Simpan' di Step 3 nanti, datanya tidak dobel/menumpuk)
    memori.daftar_belanja = memori.daftar_belanja.filter(ikan => ikan.pengepul !== namaPengepul);

    // 3. Set memori pengepul agar sistem tahu kita sedang melayani pengepul ini
    memori.pengepul_aktif = namaPengepul;

    // 4. Ubah label nama pengepul di Step 3 (Pastikan id ini sesuai dengan HTML Step 3 kamu)
    let labelPengepul = document.getElementById('nama-pengepul-terpilih');
    if (labelPengepul) {
        labelPengepul.innerText = namaPengepul;
    }

    // 5. Kosongkan & Sembunyikan semua kotak input di Step 3 terlebih dahulu
    document.querySelectorAll('.input-harga').forEach(function(kotakInput) {
        kotakInput.style.display = 'none';
        kotakInput.value = '';
    });

    // 6. SULAP: Munculkan dan isi kembali kotak input sesuai data yang diedit
    dataYangMauDiedit.forEach(function(ikan) {
        let kotakInput = document.getElementById('input-' + ikan.jenis);
        if (kotakInput) {
            kotakInput.style.display = 'block'; // Tampilkan kotak
            kotakInput.value = ikan.harga;      // Isi harganya kembali
        }
    });

    // 7. Kembalikan status Lunas/Belum Lunas ke dropdown
    if (dataYangMauDiedit.length > 0) {
        document.getElementById('pilihan-status').value = dataYangMauDiedit[0].status;
    }

    // 8. Pindah ke layar Step 3
    pindahKeStep(3);
}

let pengepulYangAkanDihapus = null;

function hapusPengepul(namaPengepul) {

    pengepulYangAkanDihapus = namaPengepul;

    // Isi nama pengepul ke modal
    document.getElementById('namaPengepulHapus').innerText = namaPengepul;

    // Tampilkan modal
    $('#modalHapusPengepul').modal('show');
}

function konfirmasiHapusPengepul() {

    // Hapus data pengepul
    memori.daftar_belanja = memori.daftar_belanja.filter(
        ikan => ikan.pengepul !== pengepulYangAkanDihapus
    );

    // Tutup modal
    $('#modalHapusPengepul').modal('hide');

    // Refresh tampilan
    gambarUlangKeranjangBelanja();

    // Jika keranjang kosong
    if (memori.daftar_belanja.length === 0) {
        pindahKeStep(2);
    }
}
    
// 8. TAHAP FINAL: KIRIM KE DATABASE LARAVEL
// Fungsi ini dipanggil saat tombol Biru Cetak ditekan
function kirimKeDatabaseLaravel(aksi) {
    if (memori.daftar_belanja.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    // TANGKAP SINYAL TOMBOL: Masukkan pilihan 'simpan' atau 'cetak' ke form
    document.getElementById('input-aksi-transaksi').value = aksi;

    // A. Isi ID Nelayan ke form rahasia
    document.getElementById('input-rahasia-nelayan').value = memori.nelayan_id;

    // B. Buat inputan rahasia untuk setiap ikan di keranjang
    let areaInputRahasia = document.getElementById('tempat-input-ikan-rahasia');
    areaInputRahasia.innerHTML = ''; // Bersihkan dulu

    memori.daftar_belanja.forEach(function(ikan, urutan) {
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][pengepul]" value="${ikan.pengepul}">`;
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][jenis]" value="${ikan.jenis}">`;
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][harga]" value="${ikan.harga}">`;
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][status_pembayaran]" value="${ikan.status}">`;
    });

    // ambil nilai admin dari input
    let admin = document.getElementById('input-admin').value || 0;

    // kirim ke input hidden
    document.getElementById('input-admin-hidden').value = admin;

    // C. Tekan tombol Submit pada form rahasia!
    document.getElementById('form-rahasia').submit();
}

function hitungTotalAkhir() {
    // 1. Gunakan variabel global totalSemuaGlobal yang diisi saat gambarUlangKeranjangBelanja
    let kotor = typeof totalSemuaGlobal !== 'undefined' ? totalSemuaGlobal : 0;

    // 2. Tampilkan Total Kotor di Step 5 jika elemennya ada
    let elKotor = document.getElementById('teks-total-kotor-step5');
    if (elKotor) {
        elKotor.innerText = "Rp " + kotor.toLocaleString('id-ID');
    }

    // 3. Ambil nilai admin
    let elAdmin = document.getElementById('input-admin');
    let nilaiAdmin = 0;
    if (elAdmin && elAdmin.value !== "") {
        nilaiAdmin = parseInt(elAdmin.value) || 0;
    }

    // 4. Hitung Bersih
    let bersih = kotor - nilaiAdmin;

    // 5. Tampilkan Hasil Akhir jika elemennya ada
    let elAkhir = document.getElementById('teks-total-akhir');
    if (elAkhir) {
        elAkhir.innerText = "Rp " + bersih.toLocaleString('id-ID');
    }

    // Simpan ke memori untuk database
    memori.biaya_admin = nilaiAdmin;
}

// PENANGKAP PESAN DARI CONTROLLER
document.addEventListener("DOMContentLoaded", function() {
    @if(session('nelayan_baru_id'))
        // Jika ada pesan nelayan baru dari Controller, langsung jalankan fungsi pilihNelayan
        let idBaru = {{ session('nelayan_baru_id') }};
        let namaBaru = "{{ session('nelayan_baru_nama') }}";
            
        // Panggil fungsi yang sudah kamu buat sebelumnya
        pilihNelayan(idBaru, namaBaru);
    @endif
});
</script>
@endsection