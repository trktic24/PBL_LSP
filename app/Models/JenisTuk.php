<?php

namespace App\Models; // <-- Pastikan namespace-nya App\Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTuk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     */
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
    public $timestamps = true; // <-- Ganti ke 'false' jika tabel Anda tidak punya timestamps
}