<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'penjualan_id';
    
    protected $fillable = [
        'pengguna_id', 'nelayan_id', 'tanggal', 
        'total_harga', 'biaya_admin', 'status_pembayaran'
    ];

    // Relasi ke Nelayan
    public function nelayan() {
        return $this->belongsTo(Nelayan::class, 'nelayan_id', 'nelayan_id');
    }

    // Relasi ke Detail (Barang/Ikan)
    public function detail() {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id', 'penjualan_id');
    }

    public function getTotalBersihAttribute() {
        return $this->total_harga - $this->biaya_admin;
    }

    // FUNGSI OTOMATIS LINK WA DENGAN TOTAL BERSIH
    public function getLinkWaAttribute()
    {
        // Cek apakah nelayan ada dan punya nomor HP
        if (!$this->nelayan || empty($this->nelayan->nomor_hp)) {
            return null; 
        }

        // 1. Bersihkan nomor HP (hanya ambil angka)
        $hp = preg_replace('/[^0-9]/', '', $this->nelayan->nomor_hp);
        
        // 2. Ubah 0 jadi 62
        if (substr($hp, 0, 1) == '0') {
            $hp = '62' . substr($hp, 1);
        }

        // 3. Hitung pendapatan bersih
        $total_bersih = $this->total_harga - $this->biaya_admin;
        $rupiah = number_format($total_bersih, 0, ',', '.');    
        $tanggal = \Carbon\Carbon::parse($this->tanggal)->locale('id')->translatedFormat('j F Y');

        // 4. Buat pesan (pakai * agar tebal di WA)
        $pesan = "Halo Pak {$this->nelayan->nama}, ini rincian penjualan hasil laut tanggal {$tanggal}. Pendapatan bersih Bapak hari ini adalah *Rp {$rupiah}*. File karcisnya silakan diunduh ya Pak. 🙏";
        
        return "https://wa.me/{$hp}?text=" . urlencode($pesan);
    }
}
