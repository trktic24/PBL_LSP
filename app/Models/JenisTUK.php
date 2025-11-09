<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTuk extends Model
{
    use HasFactory;
    
    // Model 'JenisTuk' akan mencari tabel 'jenis_tuks' secara default.
    // Jika nama tabel di database Anda adalah 'jenis_tuk' (tanpa 's'),
    // Anda harus menambahkan baris ini:
    protected $table = 'jenis_tuk'; 

    /**
     * Primary key kustom untuk tabel.
     */
    protected $primaryKey = 'id_jenis_tuk';

    /**
     * Izinkan semua kolom diisi.
     */
    protected $guarded = [];

    /**
     * Beri tahu Laravel untuk tidak mengelola timestamps 
     * (created_at & updated_at) di tabel ini.
     * (Migrasi Anda sebelumnya tidak memilikinya)
     */
    public $timestamps = false; // <-- Ganti ke 'false' jika tabel Anda tidak punya timestamps

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }
}