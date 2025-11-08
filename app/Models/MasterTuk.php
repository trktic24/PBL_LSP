<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /** Relasi ke Jadwal (Children) */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_tuk', 'id_tuk');
    }
}