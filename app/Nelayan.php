<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nelayan extends Model
{
    protected $table = 'nelayan';
    protected $primaryKey = 'nelayan_id';
    
    // Kolom yang boleh diisi
    protected $fillable = ['pengguna_id', 'nama', 'nomor_hp'];

    // Relasi balik ke pengguna (ibu nelayan)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id', 'pengguna_id');
    }

    // ==========================================
    // FUNGSI BARU: Mengubah nomor HP menjadi Link WA
    // ==========================================
    public function getLinkWaAttribute()
    {
        // Jika nomor HP kosong di database, batalkan
        if (!$this->nomor_hp) {
            return null; 
        }

        // 1. Bersihkan nomor dari spasi, strip, atau karakter selain angka
        $hp = preg_replace('/[^0-9]/', '', $this->nomor_hp);
        
        // 2. Ubah awalan angka 0 menjadi 62 (Kode Negara RI)
        if (substr($hp, 0, 1) == '0') {
            $hp = '62' . substr($hp, 1);
        }

        // 3. Susun sapaan otomatis
        $pesan = "Halo Pak {$this->nama}, ini rincian penjualan hasil laut Bapak. File karcisnya silakan diunduh ya Pak. 🙏";
        
        // 4. Kembalikan dalam bentuk Link WA lengkap
        return "https://wa.me/{$hp}?text=" . urlencode($pesan);
    }
}
