<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

<<<<<<< HEAD
    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'jadwal';

    /**
     * Kunci primer yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_jadwal';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
=======
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

>>>>>>> origin/kelompok_1
    protected $fillable = [
        'id_jenis_tuk',
        'id_tuk',
        'id_skema',
        'id_asesor',
<<<<<<< HEAD
=======
        'kuota_maksimal',
        'kuota_minimal',
>>>>>>> origin/kelompok_1
        'sesi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pelaksanaan',
        'Status_jadwal',
<<<<<<< HEAD
        'kuota_maksimal',
        'kuota_minimal',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
=======
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
>>>>>>> origin/kelompok_1
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'datetime',
    ];

<<<<<<< HEAD
    /**
     * Mendapatkan TUK (Tempat Uji Kompetensi) yang terkait dengan jadwal.
     */
    public function tuk()
=======
    // --- RELASI (Relationships) ---

    public function jenisTuk()
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    public function masterTuk()
>>>>>>> origin/kelompok_1
    {
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk');
    }

<<<<<<< HEAD
    /**
     * Mendapatkan skema yang terkait dengan jadwal.
     */
    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    /**
     * Mendapatkan asesor yang terkait dengan jadwal.
     */
    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }

    public function jenisTuk()
    /**
     * Mendapatkan jenis TUK yang terkait dengan jadwal.
     */
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }
=======
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
>>>>>>> origin/kelompok_1
}