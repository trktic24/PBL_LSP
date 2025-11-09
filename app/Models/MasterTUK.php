<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTuk extends Model
{
    use HasFactory;
    
    // Model 'MasterTuk' akan mencari tabel 'master_tuks' secara default.
    // Jika nama tabel di database Anda adalah 'master_tuk' (tanpa 's'),
    // Anda harus menambahkan baris ini:
    protected $table = 'master_tuk'; 
    

    /**
     * Kunci primer yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_tuk';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lokasi',
        'alamat_tuk',
        'kontak_tuk',
        'foto_tuk',
        'link_gmap',
    ];

    /**
     * Mendapatkan jadwal yang terkait dengan TUK.
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_tuk', 'id_tuk');
    }
}