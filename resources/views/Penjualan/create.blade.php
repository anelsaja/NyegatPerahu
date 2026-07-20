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
    
    /* DESAIN TOMBOL KOTAK */
    .grid-btn {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .btn-kotak { 
        border: 2px solid transparent;
        background-color: #e0e0e0;
        border-radius: 15px;
        padding: 15px 10px; 
        text-align: center;
        font-weight: bold;
    }

    /* Kotak pengepul normal */
    .item-pengepul {
        border: 2px solid transparent;
    }

    /* Saat aktif */
    .item-pengepul.active {
        border-color: #007bff !important;
        background-color: #e9f2ff !important;
        color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
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

    .item-ikan.active {
        border: 2px solid #007bff !important;
        color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }

    .btn-bawah-ganda { 
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%; 
        padding: 15px;
        display: flex;
        gap: 10px;
        background-color: #ffffff;
        border-top: 2px solid #f0f0f0;
    }
    
    /* Input harga */
    .input-harga {
        border: 2px solid transparent;
        display: none;
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 15px;
        margin-top: 10px;
        text-align: left;
        font-weight: bold;
    }

    #pengepul-tpi {
        display: none;
    }

    .input-harga:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
    }

    .btn-tambah-baru {
        background-color: #f4fbff !important;
        border: 2px dashed #5bc0de !important;
        color: #17a2b8 !important;
        border-radius: 15px;
        padding: 15px 10px; 
        text-align: center;
        font-weight: bold;
    }

    .card-tambah-baru {
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
        border: 2px solid transparent;
    }

    .btn-status.lunas-aktif {
        background-color: #d4edda;
        color: #155724;
        border-color: #28a745;
    }

    .btn-status.belum-aktif {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #dc3545;
    }

    /* ANIMASI PINDAH HALAMAN */
    .step-section {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }

    .step-section.active {
        display: block;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="p-3">
    <div id="step-1" class="step-section active">
        <h4 class="font-weight-bold mb-2 mt-2">Tambah Data Penjualan</h4>
        <p class="font-weight-bold">Pilih Nelayan</p>

        <div class="grid-btn">
            @foreach($nelayans as $n)
            <div class="btn-kotak" onclick="pilihNelayan({{ $n->nelayan_id }}, '{{ $n->nama }}', '{{ $n->nomor_hp ?? '' }}')">
                <div class="fotoprofil">
                    @if($n->foto_profil)
                        <img src="{{ asset('images/nelayan/' . $n->foto_profil) }}" alt="Foto {{ $n->nama }}">
                    @else
                        <i class="bi bi-person-fill" style="font-size: 35px;"></i>
                    @endif
                </div>
                {{ $n->nama }}
            </div>
            @endforeach
        </div>
        
        <div class="btn-tambah-baru shadow-sm" onclick="window.location.href='{{ route('nelayan.create', ['asal' => 'penjualan']) }}'">
            <div class="card-tambah-baru">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <span class="font-weight-bold">Tambah Nelayan Baru</span>
        </div>
        <div style="height: 120px;"></div>

        <div class="btn-bawah-ganda">
            <a href="{{ route('home') }}"
            class="btn btn-danger btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0"
            style="border-radius: 15px; width: 100%; padding: 16px 0;">
                Batal
            </a>
        </div>
    </div>

    <div id="step-2" class="step-section">
        <h4 class="font-weight-bold mb-2 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <p class="font-weight-bold">Pilih Jenis Hasil Laut</p>
        
        <div id="alert-peringatan-ikan" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 22px;"></i>
                <span style="font-size: 14px;">Silakan pilih minimal 1 ikan dan masukkan harganya</span>
            </div>
        </div>

        <div class="grid-btn" id="container-ikan">
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Lelang', this)">
                Lelang
                <input type="text" id="input-Lelang" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Timbangan', this)">
                Timbangan
                <input type="text" id="input-Timbangan" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Tebleng', this)">
                Tebleng
                <input type="text" id="input-Tebleng" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Kembung', this)">
                Kembung
                <input type="text" id="input-Kembung" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Cucut', this)">
                Cucut
                <input type="text" id="input-Cucut" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Pirek', this)">
                Pirek
                <input type="text" id="input-Pirek" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Blekutak', this)">
                Blekutak
                <input type="text" id="input-Blekutak" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Rajungan', this)">
                Rajungan
                <input type="text" id="input-Rajungan" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Windu', this)">
                Windu
                <input type="text" id="input-Windu" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak item-ikan" onclick="toggleInputIkan('Elek-Elekan', this)">
                Elek-Elekan
                <input type="text" id="input-Elek-Elekan" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
        </div>
        
        <div class="btn-tambah-baru shadow-sm" data-toggle="modal" data-target="#modalIkanBaru">
            <div class="card-tambah-baru">
                <i class="bi bi-plus-circle"></i>
            </div>
            <span class="font-weight-bold">Tambah Jenis Ikan Baru</span>
        </div>
        <div style="height: 120px;"></div>

        <div class="btn-bawah-ganda">
            <button type="button"
                    onclick="pindahKeStep(1)"
                    class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0"
                    style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
                Kembali
            </button>

            <button type="button"
                    onclick="lanjutKePengepul()"
                    class="btn btn-primary btn-lg font-weight-bold shadow-sm m-0"
                    style="border-radius: 15px; flex: 1; padding: 16px 0;">
                Lanjut
            </button>
        </div>
    </div>

    <div id="step-3" class="step-section">
        <h4 class="font-weight-bold mb-2 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <p class="font-weight-bold">Pilih Pengepul</p>

        <div id="alert-pengepul" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 22px;"></i>
                <span style="font-size: 14px;">Silakan pilih pengepul terlebih dahulu!</span>
            </div>
        </div>
        
        <div class="grid-btn" id="container-pengepul">
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Kaji Arip')">Kaji Arip</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'BBI')">BBI</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Tarom')">Tarom</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Pramono')">Pramono</div>
            <div id="pengepul-tpi" class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'TPI Banyutowo')">TPI Banyutowo</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Rossa')">Rossa</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Rini')">Rini</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Kaji Sun')">Kaji Sun</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'kaji Tino')">Kaji Tino</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Tri')">Tri</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Pi`i')">Pi`i</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Agus')">Agus</div>
            <div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, 'Tilah Prawi')">Tilah Prawi</div>
        </div>

        <div class="btn-tambah-baru shadow-sm mb-3" data-toggle="modal" data-target="#modalPengepulBaru">
            <div class="card-tambah-baru">
                <i class="bi bi-shop"></i>
            </div>
            <span class="font-weight-bold">Tambah Pengepul Baru</span>
        </div>

        <div id="alert-status" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 22px;"></i>
                <span style="font-size: 14px;">Silakan pilih status Lunas atau Hutang!</span>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <label class="font-weight-bold text-muted small d-block mb-3">Status Pembayaran</label>
                <div class="d-flex" style="gap: 10px;">
                    <div id="btn-status-lunas" class="btn-status" onclick="pilihStatus('Lunas')">Lunas</div>
                    <div id="btn-status-belum" class="btn-status" onclick="pilihStatus('Hutang')">Hutang</div>
                </div>
                <input type="hidden" id="pilihan-status" value="">
            </div>
        </div>
        <div style="height: 120px;"></div>

        <div class="btn-bawah-ganda">
            <button type="button"
                    onclick="pindahKeStep(2)"
                    class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0"
                    style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
                Kembali
            </button>

            <button type="button"
                    onclick="validasiDanSimpan()"
                    class="btn btn-primary btn-lg font-weight-bold shadow-sm m-0"
                    style="border-radius: 15px; flex: 1; padding: 16px 0;">
                Simpan
            </button>
        </div>
    </div>

    <div id="step-4" class="step-section">
        <h4 class="font-weight-bold mb-2 mt-2">Keranjang Penjualan</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Rincian Tangkapan</h6>
        <div id="area-keranjang-belanja"></div>

        <div class="card p-3 mb-4 bg-light shadow-sm" style="border-radius: 15px;">
            <div class="d-flex justify-content-between">
                <span class="font-weight-bold">Total Sementara</span>
                <strong id="total-semua" class="text-success" style="font-size: 18px;">Rp 0</strong>
            </div>
        </div>

        <button onclick="pindahKeStep(2)" class="btn-block btn-tambah-baru shadow-sm">
            <div class="card-tambah-baru">
                <i class="bi bi-plus-circle"></i>
            </div>
            <span class="font-weight-bold">Tambah Data Lainnya</span>
        </button>
        <div style="height: 120px;"></div>
        
        <div class="btn-bawah-ganda">
            <button type="button"
                    onclick="$('#modalKonfirmasiStep5').modal('show')"
                    class="btn btn-primary btn-lg font-weight-bold shadow-sm m-0"
                    style="border-radius: 15px; width: 100%; padding: 16px 0;">
                Lanjut ke Biaya Admin <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <div id="step-5" class="step-section">
        <h4 class="font-weight-bold mb-2 mt-2">Ringkasan Akhir</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <div class="form-group mb-0 mb-3">
            <label class="font-weight-bold text-dark">Catatan</label>
            <textarea id="input-catatan" class="form-control text-left" rows="3" 
                          style="border-radius: 15px; border: 2px solid #eaf6fd; font-size: 16px;" 
                          placeholder="(opsional)"></textarea>
        </div>
        
        <div class="card p-3 shadow-sm mb-3" style="border-radius: 15px;">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Total Tangkapan</span>
                <strong id="teks-total-kotor-step5">Rp 0</strong>
            </div>
            <div class="form-group border-bottom pb-3">
                <label class="font-weight-bold">Biaya Admin</label>
                <input type="text" id="input-admin" class="form-control text-left" placeholder="Rp" oninput="hitungTotalAkhir()">
            </div>
            <div class="d-flex justify-content-between mt-3">
                <span class="font-weight-bold text-success">TOTAL AKHIR</span>
                <strong id="teks-total-akhir" class="text-success" style="font-size: 24px;">Rp 0</strong>
            </div>
        </div>

        <div class="btn-bawah-ganda">
            <button type="button"
                    onclick="kirimKeDatabaseLaravel()"
                    class="btn btn-primary btn-lg font-weight-bold shadow-sm m-0"
                    style="border-radius: 15px; width: 100%; padding: 16px 0;">
                Buat Karcis dan Simpan
            </button>
        </div>
    </div>
</div>

<form id="form-rahasia" action="{{ route('penjualan.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
    <input type="hidden" name="nelayan_id" id="input-rahasia-nelayan">
    <input type="hidden" name="biaya_admin" id="input-admin-hidden">
    <input type="hidden" name="catatan" id="input-catatan-hidden">
    <div id="tempat-input-ikan-rahasia"></div>
</form>

<div class="modal fade" id="modalIkanBaru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-3" style="max-width: 100%;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold">Tambah Jenis Ikan Baru</h5>
            </div>
            <div class="modal-body pt-3">
                <div id="alert-ikan-kosong" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 18px;"></i>
                        <span style="font-size: 13px;">Nama jenis ikan tidak boleh kosong!</span>
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

<div class="modal fade" id="modalPengepulBaru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-3" style="max-width: 100%;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">  
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold">Tambah Pengepul Baru</h5>
            </div>
            <div class="modal-body pt-3">
                <div id="alert-pengepul-kosong" class="alert shadow-sm mb-3" style="display: none; border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill text-danger mr-2" style="font-size: 18px;"></i>
                        <span style="font-size: 13px;">Nama pengepul tidak boleh kosong!</span>
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

<div class="modal fade" id="modalWaPerItem" tabindex="-1" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mx-3" style="max-width: 100%;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold">Tambah Data ini dan Kirim Pesan WhatsApp</h5>
            </div>      
            <div class="modal-body pt-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="d-flex align-items-center flex-wrap mb-1">
                            <h6 class="font-weight-bold mb-0 mr-2">
                                <i class="bi bi-shop mr-1"></i> 
                                <span id="namaPengepulWa"></span>
                            </h6>
                            <span id="badgeStatusWa" class="badge rounded-pill px-2 py-1">
                                -
                            </span>
                        </div>
                    </div>
                </div>
                <div 
                    id="rincianIkanWa" 
                    class="bg-light p-3 rounded mb-3 shadow-sm"
                    style="font-size: 13px; border: 1px dashed #ccc;"
                >
                </div>
                <p class="text-muted mb-0" style="font-size: 13px;">
                    *Sudah yakin data ini benar? 
                    Data yang ditambahkan tidak dapat diubah lagi di keranjang.
                </p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button"
                    class="btn btn-light shadow-sm font-weight-bold"
                    data-dismiss="modal"
                    style="border-radius: 15px; padding: 10px 15px;"
                >
                    Batal
                </button>
                <a 
                    href="#"
                    id="tombolKirimWaItem"
                    class="btn btn-primary shadow-sm font-weight-bold px-4"
                    style="border-radius: 15px; padding: 10px 15px; text-decoration: none;"
                    onclick="eksekusiSimpanKeranjang()"
                >
                    Tambah dan Kirim
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiStep5" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-3" style="max-width: 100%;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold">Lanjut ke Biaya Admin</h5>
            </div>
            <div class="modal-body pt-3">
                <div class="alert mb-3"
                    style="
                        border-radius: 15px;
                        background-color: #fff3cd;
                        border-left: 2px solid #ffc107;
                    ">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-triangle-fill text-warning mr-2"></i>

                        <div style="font-size: 14px;">
                            Setelah lanjut ke tahap biaya admin,
                            rincian ikan yang sudah masuk keranjang
                            <b>tidak dapat diubah kembali.</b>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-0" style="font-size: 13px;">
                    *Pastikan data penjualan sudah benar sebelum melanjutkan.
                </p>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button
                    type="button"
                    class="btn btn-light shadow-sm font-weight-bold"
                    data-dismiss="modal"
                    style="border-radius: 15px; padding: 10px 15px;"
                >
                    Batal
                </button>
                <button
                    type="button"
                    class="btn btn-primary shadow-sm font-weight-bold px-4"
                    style="border-radius: 15px; padding: 10px 15px;"
                    onclick="lanjutKeStep5()"
                >
                    Lanjut
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ==========================================
// 1. MEMORI APLIKASI
// ==========================================
let memori = {
    nelayan_id: null,
    nelayan_nama: '',
    nelayan_hp: '',
    pengepul_aktif: '',
    daftar_belanja: [] 
};
let totalSemuaGlobal = 0;
let nelayanTerkunci = false;

// ==========================================
// 2. KONTROL HALAMAN (STEP)
// ==========================================
function pindahKeStep(nomor) {
    document.querySelectorAll('.step-section').forEach(s => s.style.display = 'none');
    let stepSekarang = document.getElementById('step-' + nomor);
    if (stepSekarang) stepSekarang.style.display = 'block';

    if (nomor === 5) hitungTotalAkhir();

    let tombolKembaliNelayan = document.getElementById('btn-kembali-nelayan');

    if (tombolKembaliNelayan) {
        if (nelayanTerkunci) {
            tombolKembaliNelayan.style.display = 'none';
        } else {
            tombolKembaliNelayan.style.display = 'block';
        }
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ==========================================
// 3. FUNGSI STEP 1: NELAYAN
// ==========================================
function pilihNelayan(id, nama, hp) {
    memori.nelayan_id = id;
    memori.nelayan_nama = nama;
    memori.nelayan_hp = hp || ''; 
    
    // Baris yang error (getElementById) sudah dihapus.
    // Cukup gunakan querySelectorAll untuk mengubah nama di SEMUA step sekaligus.
    document.querySelectorAll('.info-nelayan-nama-teks').forEach(teks => teks.innerText = nama);
    
    pindahKeStep(2);
}

// ==========================================
// 4. FUNGSI STEP 2: IKAN
// ==========================================
function toggleInputIkan(namaIkan, elemenKotak) {
    let kotakInput = document.getElementById('input-' + namaIkan);
    if (kotakInput.style.display === 'block') {
        kotakInput.style.display = 'none';
        kotakInput.value = '';
        elemenKotak.classList.remove('active');
    } else {
        kotakInput.style.display = 'block';
        kotakInput.focus();
        elemenKotak.classList.add('active');
    }

    cekModeLelang();
}

function cekModeLelang() {
    let inputLelang = document.getElementById('input-Lelang');
    let tpiBox = document.getElementById('pengepul-tpi');

    if (!inputLelang || !tpiBox) return;

    // cek apakah lelang aktif
    let lelangAktif =
        inputLelang.style.display === 'block' &&
        inputLelang.value !== '';

    if (lelangAktif) {

        // tampilkan TPI
        tpiBox.style.display = 'block';

        // sembunyikan pengepul lain
        document.querySelectorAll('.item-pengepul').forEach(el => {
            if (el.id !== 'pengepul-tpi') {
                el.style.display = 'none';
            }
        });

        // AUTO ACTIVE TPI
        document.querySelectorAll('.item-pengepul').forEach(el => {
            el.classList.remove('active');
        });

        tpiBox.classList.add('active');

        memori.pengepul_aktif = 'TPI Banyutowo';

    } else {

        // tampilkan pengepul selain TPI
        document.querySelectorAll('.item-pengepul').forEach(el => {
            if (el.id !== 'pengepul-tpi') {
                el.style.display = 'block';
            }
        });

        // sembunyikan TPI
        tpiBox.style.display = 'none';

        // reset active
        tpiBox.classList.remove('active');

        if (memori.pengepul_aktif === 'TPI Banyutowo') {
            memori.pengepul_aktif = '';
        }
    }
}

function simpanIkanBaru() {
    let inputElement = document.getElementById('inputIkanBaru');
    let namaIkan = inputElement.value.trim();
    let alertKosong = document.getElementById('alert-ikan-kosong');

    if (namaIkan !== "") {
        alertKosong.style.display = 'none';
        $('#modalIkanBaru').modal('hide');
        inputElement.value = ""; 

        let idAman = namaIkan.replace(/\s+/g, '_');
        let htmlKotakBaru = `
        <div class="btn-kotak" onclick="toggleInputIkan('${idAman}')">
            ${namaIkan} <span class="text-danger">*</span>
            <input type="text" id="input-${idAman}" class="input-harga format-rupiah" placeholder="Rp" onclick="event.stopPropagation()">
        </div>`;
        document.getElementById('container-ikan').insertAdjacentHTML('beforeend', htmlKotakBaru);
    } else {
        alertKosong.style.display = 'block';
        inputElement.focus();
        setTimeout(() => alertKosong.style.display = 'none', 3000);
    }
}

function lanjutKePengepul() {
    let adaIkan = false;
    let adaLelang = false; // 1. Tambahan variabel penanda ikan lelang

    document.querySelectorAll('.input-harga').forEach(input => {
        if (input.value && parseInt(input.value.replace(/\./g, '')) > 0) {
            adaIkan = true;
            
            // 2. Cek jika kotak yang sedang diisi adalah kotak "Lelang"
            if (input.id === 'input-Lelang') {
                adaLelang = true;
            }
        }
    });

    if (!adaIkan) {
        document.getElementById('alert-peringatan-ikan').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => document.getElementById('alert-peringatan-ikan').style.display = 'none', 3000);
        return;
    }
    
    document.getElementById('alert-peringatan-ikan').style.display = 'none';

    // 3. LOGIKA OTOMATISASI PENGEPUL
    if (adaLelang) {
        // Jika ada ikan Lelang, otomatis cari kotak 'TPI Banyutowo' dan klik secara sistem
        document.querySelectorAll('.item-pengepul').forEach(el => {
            if (el.innerText.trim() === 'TPI Banyutowo') {
                pilihPengepulUI(el, 'TPI Banyutowo');
            }
        });
    } else {
        // Jika tidak ada ikan lelang, bersihkan status pengepul (kembali netral)
        memori.pengepul_aktif = '';
        document.querySelectorAll('.item-pengepul').forEach(el => el.classList.remove('active'));
    }

    cekModeLelang();
    pindahKeStep(3);
}

// ==========================================
// 5. FUNGSI STEP 3: PENGEPUL & STATUS
// ==========================================
function pilihPengepulUI(elemenKotak, nama) {
    // jika pengepul yang sama diklik lagi
    if (elemenKotak.classList.contains('active')) {
        elemenKotak.classList.remove('active');
        memori.pengepul_aktif = '';
    } else {
        // hapus active dari semua
        document.querySelectorAll('.item-pengepul')
            .forEach(el => el.classList.remove('active'));
        // aktifkan yang dipilih
        elemenKotak.classList.add('active');
        memori.pengepul_aktif = nama;
    }
    document.getElementById('alert-pengepul').style.display = 'none';
}

function simpanPengepulBaru() {
    let inputElement = document.getElementById('inputPengepulBaru');
    let namaBaru = inputElement.value.trim();
    let alertKosong = document.getElementById('alert-pengepul-kosong');

    if (namaBaru !== "") {
        alertKosong.style.display = 'none';
        $('#modalPengepulBaru').modal('hide');
        inputElement.value = "";
        
        let htmlBaru = `<div class="btn-kotak item-pengepul" onclick="pilihPengepulUI(this, '${namaBaru}')">${namaBaru}<span class="text-danger">*</span></div>`;
        document.getElementById('container-pengepul').insertAdjacentHTML('beforeend', htmlBaru);
        
        let kotakBaru = document.getElementById('container-pengepul').lastElementChild;
        pilihPengepulUI(kotakBaru, namaBaru);
    } else {       
        alertKosong.style.display = 'block';
        inputElement.focus();
        setTimeout(() => alertKosong.style.display = 'none', 3000);
    }
}

function pilihStatus(status) {
    let inputStatus = document.getElementById('pilihan-status');
    let btnLunas = document.getElementById('btn-status-lunas');
    let btnBelum = document.getElementById('btn-status-belum');

    // jika status yang sama diklik lagi
    if (inputStatus.value === status) {
        inputStatus.value = '';
        btnLunas.classList.remove('lunas-aktif');
        btnBelum.classList.remove('belum-aktif');
    } else {
        inputStatus.value = status;
        btnLunas.classList.remove('lunas-aktif');
        btnBelum.classList.remove('belum-aktif');
        if (status === 'Lunas') {
            btnLunas.classList.add('lunas-aktif');
        } else {
            btnBelum.classList.add('belum-aktif');
        }
    }
    // sembunyikan alert
    document.getElementById('alert-status').style.display = 'none';
}

// Variabel penampung sementara sebelum pengguna menekan "Yakin"
let keranjangSementara = []; 

// Fungsi 1: Dipanggil saat menekan "+ Tambahkan Data Ini"
function validasiDanSimpan() {
    let apakahValid = true;

    // 1. Cek apakah Pengepul sudah dipilih
    if (!memori.pengepul_aktif || memori.pengepul_aktif === '') {
        let alertPengepul = document.getElementById('alert-pengepul');
        if (alertPengepul) {
            alertPengepul.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth'});
            setTimeout(() => { alertPengepul.style.display = 'none';}, 3000);
        }
        apakahValid = false;
        // hentikan pengecekan berikutnya
        return;
    } else {
        document.getElementById('alert-pengepul').style.display = 'none';
    }

    // 2. Cek apakah Status Pembayaran sudah dipilih
    let statusDipilih = document.getElementById('pilihan-status').value;
    let alertStatus = document.getElementById('alert-status');
    if (statusDipilih === '') {
        if (alertStatus) {
            alertStatus.style.display = 'block';
            // scroll ke bagian status pembayaran
            alertStatus.scrollIntoView({ behavior: 'smooth', block: 'center'});
            setTimeout(() => { alertStatus.style.display = 'none';}, 3000);
        }
        apakahValid = false;
    } else {
        if (alertStatus) {
            alertStatus.style.display = 'none';
        }
    }

    // Jika ada yang belum diisi (tidak valid), gulir layar ke bawah agar ibu nelayan melihat error-nya, lalu hentikan proses
    if (!apakahValid) {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        return;
    }

    // ========================================================
    // JIKA SEMUA VALIDASI LOLOS, LANJUTKAN PROSES KERANJANG
    // ========================================================
    
    keranjangSementara = []; // Kosongkan penampung
    let daftarIkanBaru = [];
    let totalHargaBaru = 0;

    // Kumpulkan data (tapi jangan sembunyikan inputannya dulu)
    document.querySelectorAll('.input-harga').forEach(function(kotakInput) {
        if (kotakInput.style.display === 'block' && kotakInput.value !== '') {
            let namaIkan = kotakInput.id.replace('input-', '').replace(/_/g, ' '); 
            let hargaIkan = parseInt(
                kotakInput.value.replace(/\./g, '')
            );
                
            keranjangSementara.push({
                pengepul: memori.pengepul_aktif,
                jenis: namaIkan,
                harga: hargaIkan,
                status: statusDipilih,
                idKotak: kotakInput.id // Simpan ID kotaknya untuk dibersihkan nanti
            });

            daftarIkanBaru.push(`- ${namaIkan}: Rp ${hargaIkan.toLocaleString('id-ID')}`);
            totalHargaBaru += hargaIkan;
        }
    });

    if (keranjangSementara.length === 0) return; // Batal jika tidak ada harga yang diisi sama sekali

    let pengepulYangBarusan = memori.pengepul_aktif;

    // Rakit Tampilan Rincian (Struk Modal)
    let rincianHtml = '<ul class="pl-3 mb-2" style="color: #495057;">';
    daftarIkanBaru.forEach(ikan => {
        rincianHtml += `<li class="mb-1">${ikan.replace('- ', '')}</li>`;
    });
    rincianHtml += '</ul>';
    rincianHtml += `<div class="border-top pt-2 mt-2 d-flex justify-content-between">
                        <span class="text-muted">Total:</span> 
                        <strong class="text-success" style="font-size: 15px;">Rp ${totalHargaBaru.toLocaleString('id-ID')}</strong>
                    </div>`;

    document.getElementById('namaPengepulWa').innerText = pengepulYangBarusan;
    document.getElementById('rincianIkanWa').innerHTML = rincianHtml;

    // Badge status
    let badgeStatus = document.getElementById('badgeStatusWa');
    badgeStatus.innerText = statusDipilih;
    if (statusDipilih === 'Lunas') {
        badgeStatus.className =
            'badge badge-success rounded-pill px-2 py-1';
    } else {
        badgeStatus.className =
            'badge badge-danger rounded-pill px-2 py-1';
    }

    // Rakit Tautan WA (Jika nelayan punya nomor HP)
    let tombolYakin = document.getElementById('tombolKirimWaItem');
    if (memori.nelayan_hp && memori.nelayan_hp.trim() !== '') {
        let hp = memori.nelayan_hp.replace(/\D/g, '');
        if (hp.startsWith('0')) hp = '62' + hp.substring(1);

        let teksWa = `Halo Pak *${memori.nelayan_nama}*, hasil tangkapan Anda telah kami jual ke pengepul *${pengepulYangBarusan}*:\n\n`;
        teksWa += daftarIkanBaru.join('\n');
        teksWa += `\n\n*Total Uang:* Rp ${totalHargaBaru.toLocaleString('id-ID')}\nTerima kasih!`;

        tombolYakin.href = `https://wa.me/${hp}?text=${encodeURIComponent(teksWa)}`;
        tombolYakin.target = "_blank"; // Buka WA di tab baru
    } else {
        // Jika tidak ada nomor HP, matikan fitur tautan WA-nya
        tombolYakin.removeAttribute("href");
        tombolYakin.removeAttribute("target");
    }

    // Tampilkan Modal Validasi Akhir sebelum dikunci
    $('#modalWaPerItem').modal('show');
}

// ==========================================
// 6. KERANJANG (MEMASUKKAN DATA & MENGGAMBAR)
// ==========================================
// Fungsi 2: Dipanggil KHUSUS saat tombol "Yakin" di modal ditekan
function eksekusiSimpanKeranjang() {
    keranjangSementara.forEach(ikan => {

        // Cari apakah ikan yang sama sudah pernah dimasukkan
        let sudahAda = memori.daftar_belanja.find(item =>
            item.pengepul === ikan.pengepul &&
            item.status === ikan.status &&
            item.jenis === ikan.jenis
        );

        if (sudahAda) {
            // Jika jenis ikan sama, cukup tambahkan harganya
            sudahAda.harga += ikan.harga;
        } else {
            // Jika belum ada, buat data baru
            memori.daftar_belanja.push({
                pengepul: ikan.pengepul,
                jenis: ikan.jenis,
                harga: ikan.harga,
                status: ikan.status
            });
        }

    });

    // 4. Perbarui gambar keranjang dan lompat ke Step 4
    gambarUlangKeranjangBelanja(); 

    // Reset semua pilihan ikan
    document.querySelectorAll('.item-ikan').forEach(el => {
        el.classList.remove('active');
    });

    // Reset semua input harga
    document.querySelectorAll('.input-harga').forEach(input => {
        input.style.display = 'none';
        input.value = '';
    });

    // Reset pengepul
    memori.pengepul_aktif = '';
    document.querySelectorAll('.item-pengepul').forEach(el => {
        el.classList.remove('active');
    });

    // Reset status pembayaran
    document.getElementById('pilihan-status').value = '';
    document.getElementById('btn-status-lunas')
        .classList.remove('lunas-aktif');

    document.getElementById('btn-status-belum')
        .classList.remove('belum-aktif');

    // Kunci nelayan setelah ada data pertama
    nelayanTerkunci = true;

    $('#modalWaPerItem').modal('hide');
    pindahKeStep(4);
}

function gambarUlangKeranjangBelanja() {
    let areaLayar = document.getElementById('area-keranjang-belanja');
    areaLayar.innerHTML = '';

    let lemariPengepul = {};
    totalSemuaGlobal = 0;

    memori.daftar_belanja.forEach(function(ikan) {

        // gabungkan pengepul + status menjadi key
        let key = ikan.pengepul + "|" + ikan.status;

        if (!lemariPengepul[key]) {
            lemariPengepul[key] = [];
        }

        lemariPengepul[key].push(ikan);
    });

    for (let key in lemariPengepul) {

        let totalHarga = 0;

        let daftarIkan = lemariPengepul[key];

        let namaPengepul = daftarIkan[0].pengepul;

        let statusTampil = daftarIkan[0].status;

        let warnaBadge =
            statusTampil === 'Lunas'
                ? 'badge-success'
                : 'badge-danger';

        // DESAIN: Tanpa tombol Edit & Hapus (Read-Only)
        let desainHTML = `
            <div class="mb-4 border-bottom pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center flex-wrap">
                        <h6 class="font-weight-bold mb-0 mr-2">
                            <i class="bi bi-shop mr-1"></i> ${namaPengepul}
                        </h6>
                        <span class="badge ${warnaBadge} rounded-pill px-2 py-1">
                            ${statusTampil}
                        </span>
                    </div>
                </div>
                <table class="info-table mt-1">
        `;

        daftarIkan.forEach(function(ikan) {
            desainHTML += `<tr><td class="text-secondary"><i class="bi bi-record-circle text-muted" style="font-size: 8px; margin-right: 5px;"></i> ${ikan.jenis.replace(/_/g, ' ')}</td><td>Rp ${ikan.harga.toLocaleString('id-ID')}</td></tr>`;
            totalHarga += ikan.harga;
        });

        totalSemuaGlobal += totalHarga; 

        desainHTML += `
                <tr>
                    <td class="text-right text-muted pt-2 border-top">Sub-total:</td>
                    <td class="pt-2 text-info border-top font-weight-bold" style="font-size: 15px;">
                        Rp ${totalHarga.toLocaleString('id-ID')}
                    </td>
                </tr>
            </table>
        </div>`;

        areaLayar.innerHTML += desainHTML;
    }
    
    document.getElementById('total-semua').innerText = "Rp " + totalSemuaGlobal.toLocaleString('id-ID');
    hitungTotalAkhir(); 
}

function lanjutKeStep5() {
    $('#modalKonfirmasiStep5').modal('hide');
    pindahKeStep(5);
}


// ==========================================
// 7. TAHAP AKHIR & SUBMIT DATABASE
// ==========================================
function hitungTotalAkhir() {
    let kotor = typeof totalSemuaGlobal !== 'undefined' ? totalSemuaGlobal : 0;
    
    let elKotor = document.getElementById('teks-total-kotor-step5');
    if (elKotor) elKotor.innerText = "Rp " + kotor.toLocaleString('id-ID');

    let elAdmin = document.getElementById('input-admin');
    let nilaiAdmin = (elAdmin && elAdmin.value !== "")
        ? parseInt(elAdmin.value.replace(/\./g, ''))
        : 0;

    let bersih = kotor - nilaiAdmin;

    let elAkhir = document.getElementById('teks-total-akhir');
    if (elAkhir) elAkhir.innerText = "Rp " + bersih.toLocaleString('id-ID');
}

function kirimKeDatabaseLaravel() {
    if (memori.daftar_belanja.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    document.getElementById('input-rahasia-nelayan').value = memori.nelayan_id;

    let areaInputRahasia = document.getElementById('tempat-input-ikan-rahasia');
    areaInputRahasia.innerHTML = ''; 

    memori.daftar_belanja.forEach(function(ikan, urutan) {
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][pengepul]" value="${ikan.pengepul}">`;
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][jenis]" value="${ikan.jenis}">`;
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][harga]" value="${ikan.harga}">`;
        areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][status_pembayaran]" value="${ikan.status}">`;
    });

    let admin = document.getElementById('input-admin').value || '0';
    admin = admin.replace(/\./g, '');
    document.getElementById('input-admin-hidden').value = admin;

    // Ambil teks catatan dari textarea dan masukkan ke input hidden
    let catatanTeks = document.getElementById('input-catatan').value || '';
    document.getElementById('input-catatan-hidden').value = catatanTeks;

    document.getElementById('form-rahasia').submit();
}

// PENANGKAP PESAN (JIKA HABIS TAMBAH NELAYAN BARU)
document.addEventListener("DOMContentLoaded", function() {

    // realtime cek lelang
    document.addEventListener('input', function(e) {
        if (e.target && e.target.id === 'input-Lelang') {
            cekModeLelang();
        }
    });

    @if(session('nelayan_baru_id'))
        let idBaru = {{ session('nelayan_baru_id') }};
        let namaBaru = "{{ session('nelayan_baru_nama') }}";
        let hpBaru = "{{ session('nelayan_baru_hp') ?? '' }}";
        
        // Masukkan hpBaru ke dalam fungsi
        pilihNelayan(idBaru, namaBaru, hpBaru);
    @endif
});

document.addEventListener('input', function(e) {

    if (
        e.target.classList.contains('input-harga') ||
        e.target.id === 'input-admin'
    ) {

        let angka = e.target.value.replace(/\D/g, '');

        e.target.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        if (e.target.id === 'input-admin') {
            hitungTotalAkhir();
        }

        cekModeLelang();
    }

});
</script>
@endsection