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

    .input-struk {
        border: 1px solid #e0e0e0;
        background-color: #fafafa;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
    }

    .input-struk:focus {
        background-color: #fff;
        border-color: #5bc0de;
    }
    
    .card-ikan {
        background-color: #ffffff;
        border-radius: 12px; 
        border: 1px solid #eaeaea;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .card-input-ikan {
        background-color: #eaf6fd;
        border: 1px solid #b8daff;
        border-radius: 8px;
        padding: 4px 10px;
    }

    .card-input-ikan input {
        border: none !important;
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
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-2 mt-2">Edit Transaksi</h4>
    <form action="{{ route('penjualan.update', $penjualan->penjualan_id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="tanggal" value="{{ $penjualan->tanggal }}">
        <input type="hidden" name="nelayan_id" value="{{ $penjualan->nelayan_id }}">
        <table class="info-table mb-3">
            <tr><td class="text-muted">Tanggal</td><td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><td class="text-muted">Nama Nelayan</td><td>{{ $penjualan->nelayan->nama ?? '-' }}</td></tr>
            <tr><td class="text-muted">Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Rincian Tangkapan</h6>
        @php
            // MANTRA AJAIB: Kelompokkan data ikan berdasarkan nama pengepulnya
            $grupPenjualan = $penjualan->detail->groupBy('nama_pengepul');
            $urutanKe = 0; // Kunci indeks unik untuk Controller
        @endphp

        <div id="area-hasil-laut">
            @foreach($grupPenjualan as $namaPengepul => $items)
            @php 
                $statusPertama = $items->first()->status_pembayaran; 
                // Buat ID unik untuk JavaScript (menghilangkan spasi pada nama)
                $idPengepul = str_replace([' ', "'", '"', '.', ','], '_', $namaPengepul);
            @endphp
            <div class="mb-4 p-3 card-ikan shadow-sm" style="border-left: 2px solid #5bc0de;" id="card-{{ $idPengepul }}">
                <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-3">
                    <div class="d-flex align-items-center">
                        <span class="bg-light text-muted mr-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 8px;">
                            <i class="bi bi-shop" style="font-size: 16px;"></i>
                        </span>
                        <input 
                            type="text"
                            list="daftar-pengepul"
                            value="{{ $namaPengepul }}"
                            class="form-control form-control-sm input-struk border-0 bg-light mr-2 shadow-sm"
                            style="font-size: 16px;"
                            onchange="ubahNamaPengepulHidden('{{ $idPengepul }}', this.value)"
                            autocomplete="off"
                            required
                        >
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <select class="form-control form-control-sm input-struk border-0 shadow-sm {{ $statusPertama == 'Lunas' ? 'bg-success text-white' : 'bg-danger text-white' }}" 
                                style="width: 100px;"
                                onchange="ubahStatusGrup('{{ $idPengepul }}', this.value); this.className = 'form-control form-control-sm input-struk border-0 shadow-sm text-white ' + (this.value === 'Lunas' ? 'bg-success' : 'bg-danger')">
                            <option value="Lunas" class="bg-light text-dark" {{ $statusPertama == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Hutang" class="bg-light text-dark" {{ $statusPertama == 'Hutang' ? 'selected' : '' }}>Hutang</option>
                        </select>

                        <button type="button" class="btn btn-sm text-danger font-weight-bold p-1 ml-2" onclick="hapusPengepulUtuh('card-{{ $idPengepul }}', '{{ $namaPengepul }}')">
                            <i class="bi bi-trash-fill" style="font-size: 20px;"></i>
                        </button>
                    </div>
                </div>

                <div id="list-ikan-{{ $idPengepul }}">
                    @foreach($items as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2" id="baris-ikan-{{ $urutanKe }}">
                        
                        <input type="hidden" name="hasil_laut[{{ $urutanKe }}][pengepul]" class="pengepul-grup-{{ $idPengepul }}" value="{{ $namaPengepul }}">
                        <input type="hidden" name="hasil_laut[{{ $urutanKe }}][status_pembayaran]" class="status-grup-{{ $idPengepul }}" value="{{ $item->status_pembayaran }}">

                        <div class="card-input-ikan mr-2" style="flex: 1; background-color: #f4fbff; border-color: #5bc0de;">
                            <input type="text" list="daftar-ikan" name="hasil_laut[{{ $urutanKe }}][jenis]" class="form-control form-control-sm font-weight-bold text-dark w-100" value="{{ $item->jenis_hasil_laut }}" placeholder="Jenis Ikan" required autocomplete="off">
                        </div>
                        
                        <div class="input-group input-group-sm mr-2" style="flex: 1.2; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0;">
                            <input type="number" name="hasil_laut[{{ $urutanKe }}][harga]" class="form-control input-struk text-right text-info nilai-harga border-0 w-100 bg-white" value="{{ intval($item->harga) }}" oninput="hitungTotalBaru()" required style="font-size: 15px;" placeholder="Rp">
                        </div>

                        <button type="button" class="btn btn-sm text-danger font-weight-bold p-1" onclick="hapusBaris('baris-ikan-{{ $urutanKe }}', 'card-{{ $idPengepul }}')">
                            <i class="bi bi-trash3-fill" style="font-size: 18px;"></i>
                        </button>
                    </div>
                    @php $urutanKe++; @endphp
                    @endforeach
                </div>

                <button type="button" class="btn btn-sm btn-outline-info mt-2 font-weight-bold w-100" style="border-radius: 8px; border-style: dashed;" onclick="tambahIkanKeGrupPengepul('{{ $namaPengepul }}', '{{ $idPengepul }}')">
                    <div class="card-tambah-baru">
                        <i class="bi bi-plus-circle" style="font-size: 20px;"></i>
                    </div>
                    <span class="font-weight-bold">Tambah Jenis Ikan Lainnya</span>
                </button>
            </div>
            @endforeach
        </div>

        <button type="button" id="btn-tambah-pengepul-baru" class="btn-block btn-tambah-baru shadow-sm mb-3">
            <div class="card-tambah-baru">
                <i class="bi bi-plus-circle mr-1"></i>
            </div>
            <span class="font-weight-bold">Tambah Data Lainnya</span>
        </button>

        <div class="form-group mb-3">
            <label class="font-weight-bold text-dark">Catatan</label>
            <textarea 
                name="catatan"
                class="form-control text-left"
                rows="3"
                style="border-radius: 15px; border: 2px solid #eaf6fd; font-size: 16px;"
                placeholder="(opsional)"
                >{{ $penjualan->catatan ?? '' }}</textarea>
        </div>

        <h6 class="font-weight-bold mt-4 mb-3 text-muted" style="font-size: 14px;">Ringkasan Akhir</h6>

        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Tangkapan</span>
                    <strong id="teks-total-kotor" style="font-size: 16px;">Rp 0</strong>
                </div>
                <div class="form-group border-bottom pb-3 mb-3">
                    <label class="font-weight-bold">Biaya Admin</label>
                    <input 
                        type="number" 
                        name="biaya_admin" 
                        id="input-biaya-admin"
                        class="form-control text-left"
                        placeholder="Rp"
                        value="{{ intval($penjualan->biaya_admin) }}"
                        oninput="hitungTotalBaru()"
                        required
                    >
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <span class="font-weight-bold text-success">TOTAL AKHIR</span>
                    <strong 
                        id="teks-total-akhir"
                        class="text-success"
                        style="font-size: 24px;"
                    >
                        Rp 0
                    </strong>
                </div>
            </div>
        </div>

        <div style="height: 150px;"></div>
        
        <div class="btn-bawah-ganda">
            <a href="{{ route('home') }}" class="btn btn-light text-secondary btn-lg font-weight-bold shadow-sm d-flex align-items-center justify-content-center m-0" style="border-radius: 15px; flex: 1; padding: 16px 0; border: 1px solid #ddd;">
                Batal
            </a>
            <button type="submit" class="btn btn-warning btn-lg font-weight-bold shadow-sm m-0" style="border-radius: 15px; flex: 1; padding: 16px 0;">
                Simpan Edit
            </button>
        </div>

        <datalist id="daftar-ikan">
            <option value="Timbangan"></option>
            <option value="Tebleng"></option>
            <option value="Kembung"></option>
            <option value="Cucut"></option>
            <option value="Pirek"></option>
            <option value="Lelang"></option>
            <option value="Blekutak"></option>
            <option value="Rajungan"></option>
            <option value="Windu"></option>
        </datalist>

        <datalist id="daftar-pengepul">
            <option value="Kaji Arip"></option>
            <option value="BBI"></option>
            <option value="Tarom"></option>
            <option value="Pramono"></option>
            <option value="TPI Banyutowo"></option>
            <option value="Rossa"></option>
            <option value="Rini"></option>
            <option value="Kaji Sun"></option>
            <option value="Kaji Tino"></option>
            <option value="Tri"></option>
            <option value="Pii"></option>
            <option value="Agus"></option>
            <option value="Tilah Prawi"></option>
        </datalist>
    </form>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-3" style="max-width: 100%;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold" id="judul-modal-hapus">
                    Hapus Data
                </h5>
            </div>
            <div class="modal-body pt-3">
                <div class="alert shadow-sm mb-3"
                    style="border-radius: 15px; background-color: #fde8ec; border-left: 2px solid #dc3545;">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-circle-fill text-danger mr-2"
                        style="font-size: 18px;"></i>
                        <span id="isi-modal-hapus"
                            style="font-size: 13px;">
                            Data akan dihapus permanen.
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button"
                        class="btn btn-light shadow-sm font-weight-bold"
                        data-dismiss="modal"
                        style="border-radius: 15px; padding: 10px 15px;">
                    Batal
                </button>
                <button type="button"
                        id="btn-konfirmasi-hapus"
                        class="btn btn-danger shadow-sm font-weight-bold px-4"
                        style="border-radius: 15px; padding: 10px 15px;">
                    Hapus
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    let fungsiHapusAktif = null;
    // Variabel penanda urutan input untuk dikirim ke Controller
    let urutanKe = {{ $penjualan->detail->count() ?? 0 }}; 

    // 1. FUNGSI SINKRONISASI STATUS
    // Saat status di judul Pengepul diubah, ubah juga semua data tersembunyi milik ikan di grup tersebut
    function ubahStatusGrup(idPengepul, statusBaru) {
        let hiddenStatusInputs = document.querySelectorAll('.status-grup-' + idPengepul);
        hiddenStatusInputs.forEach(input => {
            input.value = statusBaru;
        });
    }
    
    // Saat Pengepul baru diganti namanya, ubah juga semua data tersembunyi
    function ubahNamaPengepulHidden(idPengepul, namaBaru) {
        let hiddenNamaInputs = document.querySelectorAll('.pengepul-grup-' + idPengepul);
        hiddenNamaInputs.forEach(input => {
            input.value = namaBaru;
        });
    }

    // 2. FUNGSI TAMBAH IKAN KE DALAM GRUP YANG SUDAH ADA
    function tambahIkanKeGrupPengepul(namaPengepul, idPengepul) {
        // Cari status pembayaran yang sedang aktif di grup tersebut
        let inputStatusPertama = document.querySelector('.status-grup-' + idPengepul);
        let statusSekarang = inputStatusPertama ? inputStatusPertama.value : 'Lunas';

        let idBaris = 'baris-ikan-' + urutanKe;
        let htmlIkanBaru = `
            <div class="d-flex justify-content-between align-items-center mb-2" id="${idBaris}">
                <input type="hidden" name="hasil_laut[${urutanKe}][pengepul]" class="pengepul-grup-${idPengepul}" value="${namaPengepul}">
                <input type="hidden" name="hasil_laut[${urutanKe}][status_pembayaran]" class="status-grup-${idPengepul}" value="${statusSekarang}">

                <div class="card-input-ikan mr-2" style="flex: 1; background-color: #f4fbff; border-color: #5bc0de;">
                    <input type="text" list="daftar-ikan" name="hasil_laut[${urutanKe}][jenis]" class="form-control form-control-sm font-weight-bold text-dark w-100" placeholder="Jenis Ikan" required autocomplete="off">
                </div>
                
                <div class="input-group input-group-sm mr-2" style="flex: 1.2; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0;">
                    <input type="number" name="hasil_laut[${urutanKe}][harga]" class="form-control input-struk text-right text-info nilai-harga border-0 w-100 bg-white" placeholder="Rp" oninput="hitungTotalBaru()" required style="font-size: 15px;">
                </div>

                <button type="button" class="btn btn-sm text-danger font-weight-bold p-1" onclick="hapusBaris('${idBaris}', 'card-${idPengepul}')">
                    <i class="bi bi-trash3-fill" style="font-size: 18px;"></i>
                </button>
            </div>
        `;
        document.getElementById('list-ikan-' + idPengepul).insertAdjacentHTML('beforeend', htmlIkanBaru);
        urutanKe++;
    }

    // 3. FUNGSI BUAT KARTU PENGEPUL BARU DARI NOL
    document.getElementById('btn-tambah-pengepul-baru').addEventListener('click', function() {
        let idPengepulBaru = 'grup_baru_' + urutanKe; 
        
        let kotakBaru = `
        <div class="mb-4 p-3 card-ikan shadow-sm" style="border-left: 2px solid #5bc0de;" id="card-${idPengepulBaru}">
            <div class="d-flex justify-content-between mb-3 align-items-center border-bottom pb-3">
                <div class="d-flex align-items-center">
                    <span class="bg-light text-muted mr-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 8px;">
                        <i class="bi bi-shop" style="font-size: 16px;"></i>
                    </span>
                    <input 
                        type="text"
                        list="daftar-pengepul"
                        class="form-control form-control-sm input-struk border-0 bg-light mr-2 shadow-sm"
                        placeholder="Nama Pengepul"
                        required
                        onchange="ubahNamaPengepulHidden('${idPengepulBaru}', this.value)"
                        style="flex: 1.5; font-size: 16px; height: 35px;"
                        autocomplete="off"
                    >
                </div>
                
                <div class="d-flex align-items-center">
                    <select class="form-control form-control-sm input-struk border-0 shadow-sm bg-success text-white" 
                            style="width: 100px;"
                            onchange="ubahStatusGrup('${idPengepulBaru}', this.value); this.className = 'form-control form-control-sm input-struk border-0 shadow-sm text-white ' + (this.value === 'Lunas' ? 'bg-success' : 'bg-danger')">
                        <option value="Lunas" class="bg-light text-dark">Lunas</option>
                        <option value="Hutang" class="bg-light text-dark">Hutang</option>
                    </select>

                    <button type="button" class="btn btn-sm text-danger font-weight-bold p-1 ml-2" onclick="hapusPengepulUtuh('card-${idPengepulBaru}', 'Pengepul Ini')">
                        <i class="bi bi-trash-fill" style="font-size: 20px;"></i>
                    </button>
                </div>
            </div>

            <div id="list-ikan-${idPengepulBaru}">
                <div class="d-flex justify-content-between align-items-center mb-2" id="baris-ikan-${urutanKe}">
                    <input type="hidden" name="hasil_laut[${urutanKe}][pengepul]" class="pengepul-grup-${idPengepulBaru}" value="">
                    <input type="hidden" name="hasil_laut[${urutanKe}][status_pembayaran]" class="status-grup-${idPengepulBaru}" value="Lunas">

                    <div class="card-input-ikan mr-2" style="flex: 1; background-color: #f4fbff; border-color: #5bc0de;">
                        <input type="text" list="daftar-ikan" name="hasil_laut[${urutanKe}][jenis]" class="form-control form-control-sm font-weight-bold text-dark w-100" placeholder="Jenis Ikan" required autocomplete="off">
                    </div>
                    
                    <div class="input-group input-group-sm mr-2" style="flex: 1.2; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0;">
                        <input type="number" name="hasil_laut[${urutanKe}][harga]" class="form-control input-struk text-right text-info nilai-harga border-0 w-100 bg-white" placeholder="Rp" oninput="hitungTotalBaru()" required style="font-size: 15px;">
                    </div>

                    <button type="button" class="btn btn-sm text-danger font-weight-bold p-1" onclick="hapusBaris('baris-ikan-${urutanKe}', 'card-${idPengepulBaru}')">
                        <i class="bi bi-trash3-fill" style="font-size: 18px;"></i>
                    </button>
                </div>
            </div>
            
            <button type="button" class="btn btn-sm btn-outline-info mt-2 font-weight-bold w-100" style="border-radius: 8px; border-style: dashed;" onclick="tambahIkanKeGrupPengepulDinamis('${idPengepulBaru}')">
                <i class="bi bi-plus-circle"></i> Tambah Ikan Lainnya
            </button>
        </div>
        `;
        document.getElementById('area-hasil-laut').insertAdjacentHTML('beforeend', kotakBaru);
        urutanKe++;
    });

    // Varian tambah ikan khusus untuk Pengepul yang baru dibuat via JS
    function tambahIkanKeGrupPengepulDinamis(idPengepulBaru) {
        // Ambil nama pengepul yang diketik di select box
        // let selectPengepul = document.querySelector(`#card-${idPengepulBaru} select`).value;
        let selectPengepul = document.querySelector(`#card-${idPengepulBaru} input[list="daftar-pengepul"]`).value;
        tambahIkanKeGrupPengepul(selectPengepul, idPengepulBaru);
    }

    // 4. FUNGSI HAPUS BARIS
    function hapusBaris(idBaris, idCard) {
        bukaModalHapus(
            'Hapus Jenis Ikan',
            'Data ikan ini akan dihapus dari transaksi.',
            function () {
                let elemen = document.getElementById(idBaris);
                if(elemen) {
                    elemen.remove();
                    hitungTotalBaru();
                    let daftarIkan = document.getElementById(
                        'list-ikan-' + idCard.replace('card-', '')
                    );
                    if (daftarIkan.children.length === 0) {
                        document.getElementById(idCard).remove();
                    }
                }
            }
        );
    }

    // FUNGSI HAPUS SATU KARTU PENGEPUL PENUH
    function hapusPengepulUtuh(idCard, namaPengepul) {
        bukaModalHapus(
            'Hapus Semua Data Pengepul',
            `Semua jenis ikan dan harga dari ${namaPengepul} akan ikut terhapus.`,    
            function () {
                let elemenCard = document.getElementById(idCard);
                if (elemenCard) {
                    elemenCard.remove();
                    hitungTotalBaru();
                }
            }
        );
    }

    function bukaModalHapus(judul, isi, callbackHapus) {
        document.getElementById('judul-modal-hapus').innerText = judul;
        document.getElementById('isi-modal-hapus').innerText = isi;

        fungsiHapusAktif = callbackHapus;

        $('#modalHapus').modal('show');
    }

    document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function () {
        if (fungsiHapusAktif) {
            fungsiHapusAktif();
        }

        $('#modalHapus').modal('hide');
    });

    // 5. FUNGSI KALKULATOR TOTAL
    function hitungTotalBaru() {
        let totalKotor = 0;
        let semuaHarga = document.querySelectorAll('.nilai-harga');
        
        semuaHarga.forEach(function(inputBox) {
            let angka = parseInt(inputBox.value);
            if (!isNaN(angka)) {
                totalKotor += angka;
            }
        });

        let biayaAdmin = parseInt(document.getElementById('input-biaya-admin').value);
        if (isNaN(biayaAdmin)) {
            biayaAdmin = 0;
        }

        let totalAkhir = totalKotor - biayaAdmin;

        document.getElementById('teks-total-kotor').innerText = 'Rp ' + totalKotor.toLocaleString('id-ID');
        document.getElementById('teks-total-akhir').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');
    }

    // Hitung total di awal saat halaman pertama kali dibuka
    window.onload = function() {
        hitungTotalBaru();
    };
</script>

@endsection