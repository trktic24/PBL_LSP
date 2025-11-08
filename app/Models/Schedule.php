<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    protected $guarded = [];

    /**
     * Tentukan kolom yang harus diperlakukan sebagai tanggal.
     */
    protected $casts = [
        // tanggal_mulai dan tanggal_selesai dihapus
        'tanggal_pelaksanaan' => 'date', // Diubah ke 'date'
    ];

    /* --- RELASI --- */

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function tuk()
    {
        return $this->belongsTo(Tuk::class, 'id_tuk', 'id_tuk');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }

    public function jenisTuk()
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    /**
     * (BARU) Relasi ke model Asesi.
     */
    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}