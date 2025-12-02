<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ia11 extends Model
{
    protected $table = 'ia11';
    protected $primaryKey = 'id_ia11';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_spesifikasi_produk_ia11',
        'rancangan_produk',
        'nama_produk',
        'standar_industri',
        'tanggal_pengoperasian',
        'gambar_produk',
    ];

    /**
     * Casts: Menginstruksikan Laravel untuk mengkonversi kolom JSON 
     * menjadi array/object PHP secara otomatis.
     */
    protected $casts = [
        'rancangan_produk' => 'array',
    ];
}