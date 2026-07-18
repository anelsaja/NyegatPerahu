<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nelayan extends Model
{
    protected $table = 'nelayan';
    protected $primaryKey = 'nelayan_id';
    protected $fillable = ['pengguna_id', 'foto_profil', 'nama', 'nomor_hp', ];

    // Relasi balik ke pengguna (ibu nelayan)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id', 'pengguna_id');
    }

}
