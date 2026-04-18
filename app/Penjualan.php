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
}
