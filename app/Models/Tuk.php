<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tuk extends Model
{
    protected $table = 'master_tuk'; 
    
    protected $primaryKey = 'id_tuk'; 
    
    // public $timestamps = false; // Aktifkan jika tabel tidak punya created_at/updated_at

    protected $fillable = [
        // Tambahkan nama-nama kolom TUK di sini, contoh: 'nama', 'alamat'
    ];
}