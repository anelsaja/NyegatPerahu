@extends('layouts.app')
@section('content')

<style>
    /* 1. PENGATURAN TAMPILAN DASAR */
    .bottom-nav { display: none !important; } /* Sembunyikan menu bawah */
    .mobile-container { padding-bottom: 100px !important; } /* Ruang untuk tombol Batal */

    .header-nyegat { background-color: #d8efff; padding: 15px; text-align: center; }
    .title-logo { font-weight: 900; font-size: 20px; color: #333; line-height: 1.2; }
    .title-logo span { color: #5bc0de; }
    
    .info-table { width: 100%; margin-bottom: 15px; font-size: 13px; }
    .info-table td { padding: 4px 0; border-bottom: 1px dashed #eee; }
    .info-table td:last-child { text-align: right; font-weight: bold; }
    
    /* 2. DESAIN TOMBOL KOTAK (NELAYAN & PENGEPUL) */
    .grid-btn { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
    .btn-kotak { 
        background-color: #e0e0e0; border-radius: 15px; padding: 15px 10px; 
        text-align: center; border: 3px solid transparent; cursor: pointer; transition: 0.2s; color: #333; font-weight: bold;
    }
    .btn-kotak:active { transform: scale(0.95); background-color: #d0d0d0; }
    .icon-box { width: 50px; height: 50px; border: 3px solid #333; border-radius: 8px; margin: 0 auto 10px auto; display: flex; align-items: center; justify-content: center; font-size: 24px;}
    
    /* Input harga disembunyikan di awal */
    .input-harga { display: none; width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 8px; margin-top: 10px; text-align: right; font-weight: bold;}
    
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
    .btn-batal {
        display: block;
        width: 100%;
        background: red;
        color: white;
        border-radius: 15px;
        padding: 18px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        text-decoration: none !important;
        border: none;
    }

    /* Hover */
    .btn-batal:hover {
        color: white;
    }

    /* Desain Khusus Kartu Tambah Nelayan */
    .btn-tambah-baru {
        background-color: #f4fbff !important; /* Warna biru super muda */
        border: 2px dashed #5bc0de !important; /* Garis putus-putus biru */
        color: #17a2b8 !important; /* Teks biru */
    }
    
    .btn-tambah-baru:active {
        background-color: #eaf6fd !important;
        border-color: #17a2b8 !important;
    }

    .icon-box-tambah {
        font-size: 28px;
        color: #5bc0de;
        margin-bottom: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 45px;
    }
    
    /* 3. ANIMASI PINDAH HALAMAN */
    .step-section { display: none; animation: fadeIn 0.3s ease-in-out; }
    .step-section.active { display: block; } /* Hanya yang punya class 'active' yang tampil */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="p-3">
    
    <div id="step-1" class="step-section active">
        <h4 class="font-weight-bold mb-4 mt-2">Tambah Data Penjualan</h4>
        <p class="font-weight-bold">Pilih Nelayan</p>
        
        <div class="grid-btn">
            @foreach($nelayans as $n)
            <div class="btn-kotak" onclick="pilihNelayan({{ $n->nelayan_id }}, `{{ $n->nama }}`)">
                <div class="icon-box">👤</div>
                {{ $n->nama }}
            </div>
            @endforeach
        </div>
        <div class="btn-kotak btn-tambah-baru shadow-sm" onclick="window.location.href='{{ route('nelayan.create') }}'">
            <div class="icon-box-tambah">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <span class="font-weight-bold">Nelayan Baru</span>
        </div>
        <div class="btn-bawah">
            <a href="{{ route('home') }}" class="btn-batal">
                <i class="bi bi-x-circle"></i>
                <span>Batal</span>
            </a>
        </div>
    </div>

    <div id="step-2" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td id="info-nelayan-nama">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>
        
        <a href="javascript:void(0)" onclick="pindahKeStep(1)" class="btn-batal" style="background-color: whitesmoke; color: black;">
            <i class="bi bi-arrow-left"></i> Kembali Pilih Nelayan
        </a>
        <div style="height: 20px;"></div>

        <p class="font-weight-bold">Pilih Pengepul</p>
        <div class="grid-btn">
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Arip')"><div class="icon-box">🐟</div>Kaji Arip</div>
            <div class="btn-kotak" onclick="pilihPengepul('BBI')"><div class="icon-box">🏢</div>BBI</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tarom')"><div class="icon-box">🚛</div>Tarom</div>
            <div class="btn-kotak" onclick="pilihPengepul('Panggang')"><div class="icon-box">🔥</div>Panggang</div>
        </div>

        <div class="btn-kotak btn-tambah-baru shadow-sm mt-3" data-toggle="modal" data-target="#modalPengepulBaru">
            <div class="icon-box-tambah">
                <i class="bi bi-shop"></i>
            </div>
            <span class="font-weight-bold">Pengepul Baru</span>
        </div>
    </div>

    <div id="step-3" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>


        <a href="javascript:void(0)" onclick="pindahKeStep(2)" class="btn-batal" style="background-color: whitesmoke; color: black;">
            <i class="bi bi-arrow-left"></i> Kembali Pilih Pengepul
        </a>
        <div style="height: 20px;"></div>

        
        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Pengepul: <span id="info-pengepul-nama" class="text-info">-</span></h6>
        
        <p class="font-weight-bold">Pilih Jenis Nama Hasil Laut</p>
        <div class="grid-btn">
            <div class="btn-kotak" onclick="toggleInputIkan('Blekutak')">
                <div class="icon-box">🦑</div>Blekutak
                <input type="number" id="input-Blekutak" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Rajungan')">
                <div class="icon-box">🦀</div>Rajungan
                <input type="number" id="input-Rajungan" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Windu')">
                <div class="icon-box">🦐</div>Windu
                <input type="number" id="input-Windu" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
            <div class="btn-kotak" onclick="toggleInputIkan('Elek-Elekan')">
                <div class="icon-box">🐠</div>Elek-Elekan
                <input type="number" id="input-Elek-Elekan" class="input-harga" placeholder="Rp" onclick="event.stopPropagation()">
            </div>
        </div>
        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <label class="font-weight-bold text-muted small">Status Pembayaran</label>
                <select name="status_pembayaran" id="pilihan-status" class="form-control border-0 bg-light" style="border-radius: 10px; font-weight: bold;">
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                </select>
            </div>
        </div>
        <div style="height: 50px;"></div>

        <div class="btn-bawah">
            <button onclick="simpanKeKeranjang()" class="btn-batal" style="background-color: green;">
            + Tambahkan Data Ini
            </button>
        </div>
    </div>

    <div id="step-4" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
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
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Arip')"><div class="icon-box">🐟</div>Kaji Arip</div>
            <div class="btn-kotak" onclick="pilihPengepul('BBI')"><div class="icon-box">🏢</div>BBI</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tarom')"><div class="icon-box">🚛</div>Tarom</div>
            <div class="btn-kotak" onclick="pilihPengepul('Panggang')"><div class="icon-box">🔥</div>Panggang</div>
        </div>
        <div style="height: 100px;"></div>

        <div class="btn-bawah">
            <button onclick="pindahKeStep(5)" class="btn-batal" style="background-color: green;">
                Lanjut ke Pembayaran
            </button>
        </div>
    </div>

    <div id="step-5" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <div id="area-keranjang-belanja"></div>
        
        <div class="card p-3 shadow-sm mb-4">
            <div class="d-flex justify-content-between mb-3">
                <span class="text-muted">Total Tangkapan</span>
                <strong id="teks-total-kotor-step5">Rp 0</strong>
            </div>

            <div class="form-group border-bottom pb-3">
                <label>Potongan Biaya Admin</label>
                <input type="number" id="input-admin" class="form-control form-control-lg text-right" placeholder="0" oninput="hitungTotalAkhir()">
            </div>

            <div class="d-flex justify-content-between mt-3">
                <span class="font-weight-bold text-success">TOTAL AKHIR</span>
                <strong id="teks-total-akhir" class="text-success" style="font-size: 24px;">Rp 0</strong>
            </div>
        </div>

        <div class="d-flex mb-4" style="gap: 10px;">
            <button type="button" onclick="kirimKeDatabaseLaravel('simpan')" class="btn btn-outline-success font-weight-bold shadow-sm" style="flex: 1; border-radius: 10px; border-width: 2px;">
                <i class="bi bi-floppy-fill mr-1"></i> Simpan Saja
            </button>

            <button type="button" onclick="kirimKeDatabaseLaravel('cetak')" class="btn btn-primary font-weight-bold shadow-sm text-white" style="flex: 1; border-radius: 10px;">
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
      <div class="modal-dialog modal-dialog-centered"> <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
          
          <div class="modal-header border-bottom-0 pb-0">
            <h5 class="modal-title font-weight-bold" id="modalPengepulLabel">Tambah Pengepul Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          
          <div class="modal-body pt-3">
            <div class="form-group mb-0">
                <label class="text-muted small font-weight-bold">Nama Pengepul</label>
                <input type="text" id="inputPengepulBaru" class="form-control form-control-lg font-weight-bold shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; background-color: #f8fcff; color: #495057;" placeholder="Ketik nama pengepul di sini...">
            </div>
          </div>
          
          <div class="modal-footer border-top-0 pt-0">
            <button type="button" class="btn btn-light shadow-sm font-weight-bold" data-dismiss="modal" style="border-radius: 10px; color: #6c757d;">Batal</button>
            
            <button type="button" class="btn btn-success shadow-sm font-weight-bold px-4" style="border-radius: 10px; background-color: #08a10b;" onclick="simpanPengepulBaru()">
                Lanjut <i class="bi bi-arrow-right"></i>
            </button>
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

    // 3. SAAT KLIK NAMA NELAYAN (Step 1)
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

    // 4. SAAT KLIK NAMA PENGEPUL (Step 2)
    function pilihPengepul(namaPengepul) {
        // Catat di memori
        memori.pengepul_aktif = namaPengepul;
        
        // Tampilkan nama pengepul di judul Step 3
        document.getElementById('info-pengepul-nama').innerText = namaPengepul;
        
        // Lanjut ke Step 3
        pindahKeStep(3);
    }

    // 5. SAAT KLIK KOTAK IKAN (Step 3)
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

    // 6. SAAT KLIK TOMBOL "+ TAMBAHKAN DATA" (Step 3)
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
            gambarUlangKeranjangBelanja(); // Perbarui tampilan struk
            pindahKeStep(4); // Lanjut ke Step 4
        } else {
            alert("Pilih minimal 1 ikan dan masukkan harganya ya Bu!");
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
                        <h6 class="font-weight-bold mb-0 text-primary">👤 ${namaPengepul}</h6>
                        <div>
                            <span class="badge ${warnaBadge} rounded-pill px-2 mr-1">
                                ${statusTampil}
                            </span>
                            
                            <button class="btn btn-sm btn-outline-info py-0 px-2 mr-1" style="border-radius: 6px; font-size: 12px; font-weight: bold;" onclick="editPengepul('${namaPengepul}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger py-0 px-2" style="border-radius: 6px; font-size: 12px; font-weight: bold;" onclick="hapusPengepul('${namaPengepul}')">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>

                    <table class="info-table mt-1">
            `;

            lemariPengepul[namaPengepul].forEach(function(ikan) {
                desainHTML += `<tr><td class="text-dark">${ikan.jenis}</td><td>Rp ${ikan.harga.toLocaleString('id-ID')}</td></tr>`;
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

    // FUNGSI BARU: Tarik data ke Step 3 untuk diedit
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

    // FUNGSI BARU: Menghapus satu pengepul dari keranjang Step 4
    function hapusPengepul(namaPengepul) {
        // Berikan peringatan agar tidak tidak sengaja kepencet
        let yakin = confirm(`Yakin ingin membatalkan dan menghapus transaksi untuk pengepul ${namaPengepul}?`);
        
        if (yakin) {
            // 1. Buang/Saring semua ikan yang BUKAN milik pengepul yang dihapus
            memori.daftar_belanja = memori.daftar_belanja.filter(ikan => ikan.pengepul !== namaPengepul);
            
            // 2. Gambar ulang layar Step 4 dengan data terbaru
            gambarUlangKeranjangBelanja();
            
            // 3. Jika keranjang ternyata jadi kosong melompong, kembalikan ke Step 2 (Pilih Pengepul)
            if (memori.daftar_belanja.length === 0) {
                alert("Keranjang kosong. Silakan pilih pengepul kembali.");
                pindahKeStep(2);
            }
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
// Fungsi untuk menghapus ikan dari keranjang sebelum simpan
function hapusIkan(index) {
    if(confirm("Hapus data ikan ini?")) {
        memori.daftar_belanja.splice(index, 1); // Hapus 1 data dari array
        gambarUlangKeranjangBelanja();
        
        // Jika keranjang kosong, balik ke step 3
        if(memori.daftar_belanja.length === 0) pindahKeStep(3);
    }
}

// Fungsi untuk edit (mengembalikan data ke input step 3)
function editIkan(index) {
    let ikan = memori.daftar_belanja[index];
    memori.pengepul_aktif = ikan.pengepul;
    
    // Tampilkan input harga di step 3 dan isi nilainya
    let inputIkan = document.getElementById('input-' + ikan.jenis);
    if(inputIkan) {
        inputIkan.style.display = 'block';
        inputIkan.value = ikan.harga;
    }
    
    // Hapus data lama di keranjang agar tidak double saat disimpan ulang
    memori.daftar_belanja.splice(index, 1);
    
    pindahKeStep(3);
}
    // Fungsi untuk memproses pop-up
    function simpanPengepulBaru() {
        // 1. Ambil nilai yang diketik dari kolom input
        let inputElement = document.getElementById('inputPengepulBaru');
        let namaBaru = inputElement.value.trim();

        // 2. Cek apakah tidak kosong
        if (namaBaru !== "") {
            // Tutup pop-up modal secara otomatis
            $('#modalPengepulBaru').modal('hide');
            
            // Kosongkan kembali kolom input (jika pengguna kembali ke step 2 nanti)
            inputElement.value = "";
            
            // Lemparkan namanya ke fungsi pilihPengepul (Otomatis masuk ke Step 3)
            pilihPengepul(namaBaru);
        } else {
            // Beri peringatan kecil jika ibu nelayan lupa mengisi tapi memencet "Lanjut"
            alert("Nama pengepul tidak boleh kosong, Bu!");
            inputElement.focus();
        }
    }

// PENANGKAP PESAN DARI CONTROLLER
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('nelayan_baru_id'))
            // Jika ada pesan nelayan baru dari Controller, langsung jalankan fungsi pilihNelayan
            let idBaru = {{ session('nelayan_baru_id') }};
            let namaBaru = "{{ session('nelayan_baru_nama') }}";
            
            // Panggil fungsi yang sudah kamu buat sebelumnya
            pilihNelayan(idBaru, namaBaru);
            
            // Opsional: Tampilkan alert kecil agar user tahu datanya berhasil
            // alert('Berhasil menambahkan ' + namaBaru + ', silakan lanjut pilih pengepul!');
        @endif
    });
</script>
@endsection