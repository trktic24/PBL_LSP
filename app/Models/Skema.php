<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    use HasFactory;
    protected $table = 'master_skema';
    protected $guarded = ['id'];

    public function unitKompetensi()
    {
        return $this->hasMany(UnitKompetensi::class, 'skema_id');
    }

    // TAMBAHKAN FUNGSI INI:
    /**
     * Asesor yang terhubung dengan Skema ini.
     */
    public function asesor()
    {
        return $this->belongsToMany(
            Asesor::class,          // Model tujuan
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_skema',             // Foreign key di pivot untuk model ini
            'id_asesor'             // Foreign key di pivot untuk model tujuan
        );
    }
}