<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tuk extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang digunakan di database
    protected $table = 'master_tuk';

    // Menentukan Primary Key dari tabel
    protected $primaryKey = 'id_tuk';

    // Kolom-kolom yang dapat diisi secara massal (sesuai dengan kolom di tabel master_tuk)
    protected $fillable = [
        'nama',
        'lokasi',
        'alamat_tuk',
        'kontak_tuk',
        'foto_tuk',
        'link_gmap',
    ];

    // Kolom tanggal akan secara otomatis dikelola oleh Laravel
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}