<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'detail_id';
    public $timestamps = false; // Kita tidak butuh created_at di tabel detail
    
    protected $fillable = ['penjualan_id', 'nama_pengepul', 'jenis_hasil_laut', 'harga', 'status_pembayaran'];
}
