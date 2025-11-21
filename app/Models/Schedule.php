<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Kita harus meng-import class-class ini
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesor;
use App\Models\JenisTuk;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    protected $guarded = [];

    /**
     * Tentukan kolom yang harus diperlakukan sebagai tipe data tertentu.
     * (Disesuaikan dengan migrasi fiks Anda)
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'date',
        'waktu_mulai' => 'datetime:H:i', 
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
     * Relasi: Jadwal memiliki banyak Pendaftar (DataSertifikasiAsesi).
     */
    public function pendaftar()
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }


}