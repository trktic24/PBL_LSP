<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTuk extends Model
{
    use HasFactory;

    // Nama tabel non-konvensional (master_tuk)
    protected $table = 'master_tuk';
    
    // Primary key non-konvensional
    protected $primaryKey = 'id_tuk';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_lokasi',
        'alamat_tuk',
        'kontak_tuk',
        'foto_tuk',
        'link_gmap',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Jadwal.
     * Foreign Key di tabel 'jadwals' adalah 'id_tuk'.
     * Catatan: Parameter ketiga ('id_tuk') dihapus karena redundan.
     */
    public function jadwal()
    {
        // Parameter 1: Nama Model Target (Jadwal::class)
        // Parameter 2: Foreign Key di tabel 'jadwals' ('id_tuk')
        return $this->hasMany(Jadwal::class, 'id_tuk');
    }
}