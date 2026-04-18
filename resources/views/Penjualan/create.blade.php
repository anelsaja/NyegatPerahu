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
    
    /* Tombol Batal Melayang di Bawah */
    .btn-bawah { 
        position: fixed;
        bottom: 40px; /* di atas bottom nav */
        left: 50%;
        transform: translateX(-50%);
        width: 98%;
        background: red;
        color: white;
        border-radius: 15px;
        padding: 20px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        text-decoration: none !important;
    }
    .btn-bawah a {
        color: white;
        text-decoration: none !important;
    }
    
    .btn-bawahh { 
        position: fixed;
        bottom: 150px; /* di atas bottom nav */
        left: 50%;
        transform: translateX(-50%);
        width: 98%;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        text-decoration: none !important;
    }
    .btn-bawahh a {
        color: white;
        text-decoration: none !important;
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
    </div>

    <div id="step-2" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td id="info-nelayan-nama">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <button onclick="pindahKeStep(1)" class="btn btn-sm btn-light text-secondary mb-3 shadow-sm" style="border-radius: 8px; font-weight: bold;">
            <i class="bi bi-arrow-left mr-1"></i> Kembali Pilih Nelayan
        </button>
        
        <p class="font-weight-bold">Pilih Pengepul</p>
        <div class="grid-btn">
            <div class="btn-kotak" onclick="pilihPengepul('Kaji Arip')"><div class="icon-box">🐟</div>Kaji Arip</div>
            <div class="btn-kotak" onclick="pilihPengepul('BBI')"><div class="icon-box">🏢</div>BBI</div>
            <div class="btn-kotak" onclick="pilihPengepul('Tarom')"><div class="icon-box">🚛</div>Tarom</div>
            <div class="btn-kotak" onclick="pilihPengepul('Panggang')"><div class="icon-box">🔥</div>Panggang</div>
        </div>
    </div>

    <div id="step-3" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        <table class="info-table mb-3">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <button onclick="pindahKeStep(2)" class="btn btn-sm btn-light text-secondary mb-3 shadow-sm" style="border-radius: 8px; font-weight: bold;">
            <i class="bi bi-arrow-left mr-1"></i> Kembali Pilih Pengepul
        </button>
        
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
        <div style="height: 100px;"></div>
        <div class="btn-bawahh">
            <button onclick="simpanKeKeranjang()" class="btn btn-success btn-lg btn-block font-weight-bold mt-4 shadow-sm" style="border-radius: 10px;">
            + Tambahkan Data Ini
            </button>
        </div>
    </div>

    <div id="step-4" class="step-section">
        <h4 class="font-weight-bold mb-3 mt-2">Tambah Data Penjualan</h4>
        
        <button onclick="kirimKeDatabaseLaravel()" class="btn btn-primary btn-lg btn-block font-weight-bold mb-4 shadow" style="border-radius: 10px; background-color: #007bff;">
            <i class="fas fa-print"></i> Cetak & Simpan Transaksi
        </button>

        <table class="info-table mb-4">
            <tr><td>Tanggal</td><td>{{ date('d M Y') }}</td></tr>
            <tr><td>Nama Nelayan</td><td class="info-nelayan-nama-teks">-</td></tr>
            <tr><td>Ibu-ibu Nelayan</td><td class="text-info">{{ Auth::user()->nama }}</td></tr>
        </table>

        <div id="area-keranjang-belanja"></div>
        <div class="card p-3 mt-3 shadow-sm" style="border-radius: 12px;">
    
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Total Semua</span>
            <strong id="total-semua">Rp 0</strong>
        </div>

        <div class="form-group mb-2">
            <label class="text-muted">Biaya Admin</label>
            <input type="number" id="input-admin" class="form-control text-right" placeholder="Rp 0" oninput="hitungTotalAkhir()">
        </div>

        <div class="d-flex justify-content-between mt-2 border-top pt-2">
            <span class="font-weight-bold">Total Akhir</span>
            <strong id="total-akhir" class="text-success">Rp 0</strong>
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
</div>

<div class="btn-bawah">
    <a href="{{ route('home') }}" 
       class="w-100 shadow-sm py-3 text-center"
       style="border-radius: 12px; font-size: 16px;">
        
        <div style="font-size:20px;">⊗</div>
        Batal
    </a>
</div>

<form id="form-rahasia" action="{{ route('penjualan.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
    <input type="hidden" name="nelayan_id" id="input-rahasia-nelayan">
    
    <input type="hidden" name="status_pembayaran" id="input-rahasia-status"> 
    
    <input type="hidden" name="biaya_admin" id="input-admin-hidden">
    <div id="tempat-input-ikan-rahasia"></div>
</form>

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
    function pindahKeStep(nomorStep) {
        document.querySelectorAll('.step-section').forEach(function(bagian) {
            bagian.classList.remove('active'); // Sembunyikan semua
        });
        document.getElementById('step-' + nomorStep).classList.add('active'); // Tampilkan 1 saja
        window.scrollTo(0, 0); // Gulir layar ke paling atas
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
        
        // Tentukan warna badge: Lunas (Success/Hijau), Belum Lunas (Warning/Kuning)
        let warnaBadge = (statusTampil === 'Lunas') ? 'badge-success' : 'badge-warning';

        let desainHTML = `
            <div class="mb-4 border-bottom pb-2">
                <h6 class="font-weight-bold d-inline-block">Pengepul: ${namaPengepul}</h6>
                
                <span class="badge ${warnaBadge} float-right rounded-pill px-2">
                    ${statusTampil}
                </span>

                <table class="info-table mt-1">
        `;

        lemariPengepul[namaPengepul].forEach(function(ikan) {
            desainHTML += `<tr><td class="text-dark">${ikan.jenis}</td><td>Rp ${ikan.harga.toLocaleString('id-ID')}</td></tr>`;
            totalHarga += ikan.harga;
        });

        totalSemuaGlobal += totalHarga; // akumulasi total semua

        desainHTML += `
                <tr>
                    <td class="text-right text-muted pt-2">Total:</td>
                    <td class="pt-2 text-info" style="font-size: 15px;">
                        Rp ${totalHarga.toLocaleString('id-ID')}
                    </td>
                </tr>
                </table>
            </div>
        `;

        areaLayar.innerHTML += desainHTML;
    }

    // tampilkan total semua
    document.getElementById('total-semua').innerText = 
        "Rp " + totalSemuaGlobal.toLocaleString('id-ID');

    hitungTotalAkhir(); // langsung hitung total akhir
}
    // 8. TAHAP FINAL: KIRIM KE DATABASE LARAVEL
    // Fungsi ini dipanggil saat tombol Biru Cetak ditekan
    function kirimKeDatabaseLaravel() {
        if (memori.daftar_belanja.length === 0) {
            alert("Keranjang masih kosong!");
            return;
        }

        // A. Isi ID Nelayan ke form rahasia
        document.getElementById('input-rahasia-nelayan').value = memori.nelayan_id;

        // TAMBAHKAN BARIS INI: Ambil status Lunas/Belum Lunas yang dipilih user
        document.getElementById('input-rahasia-status').value = document.getElementById('pilihan-status').value;

        // B. Buat inputan rahasia untuk setiap ikan di keranjang
        let areaInputRahasia = document.getElementById('tempat-input-ikan-rahasia');
        areaInputRahasia.innerHTML = ''; // Bersihkan dulu

        memori.daftar_belanja.forEach(function(ikan, urutan) {
            areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][pengepul]" value="${ikan.pengepul}">`;
            areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][jenis]" value="${ikan.jenis}">`;
            areaInputRahasia.innerHTML += `<input type="hidden" name="hasil_laut[${urutan}][harga]" value="${ikan.harga}">`;
        });

        // ambil nilai admin dari input
        let admin = document.getElementById('input-admin').value || 0;

        // kirim ke input hidden
        document.getElementById('input-admin-hidden').value = admin;

        // C. Tekan tombol Submit pada form rahasia!
        document.getElementById('form-rahasia').submit();
    }

    function hitungTotalAkhir() {
    let admin = document.getElementById('input-admin').value;
    admin = admin ? parseInt(admin) : 0;

    let totalAkhir = totalSemuaGlobal - admin;

    document.getElementById('total-akhir').innerText = 
        "Rp " + totalAkhir.toLocaleString('id-ID');
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
</script>
@endsection