<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_jenis_tuk',
        'id_tuk',
        'id_skema',
        'id_asesor',
        'kuota_maksimal',
        'kuota_minimal',
        'sesi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pelaksanaan',
        'Status_jadwal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'datetime',
    ];

    // --- RELASI (Relationships) ---

    public function jenisTuk()
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    public function masterTuk()
    {
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk');
    }

    public function skema()
    {
        // Asumsi nama model adalah Skema
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function asesor()
    {
        // Asumsi nama model adalah Asesor
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }
}