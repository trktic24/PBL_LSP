<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'master_tuk';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_tuk';

    /**
     * Izinkan semua kolom diisi.
     */
    protected $guarded = [];

    // Kita tidak bisa membuat relasi ke jenis_tuk di sini
    // karena tidak ada foreign key di tabel master_tuk.
}