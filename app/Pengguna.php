<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

// Penting: Kita menggunakan Authenticatable agar bisa menggunakan fitur Auth::login()
class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'pengguna_id';
    
    // Kolom yang diizinkan untuk diisi otomatis oleh Google
    protected $fillable = ['nama', 'email', 'google_id'];
}