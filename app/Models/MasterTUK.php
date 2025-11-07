<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTuk extends Model
{
    use HasFactory;

    protected $table = 'master_tuk';
    protected $primaryKey = 'id_tuk';

    protected $fillable = [
        'nama_lokasi',
        'alamat_tuk',
        'kontak_tuk',
        'foto_tuk',
        'link_gmap',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Jadwal.
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_tuk', 'id_tuk');
    }
}