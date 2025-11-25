<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

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
    protected $fillable = [
        'id_jenis_tuk',
        'id_tuk',
        'id_skema',
        'id_asesor',
        'sesi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pelaksanaan',
        'waktu_mulai',
        'Status_jadwal',
        'kuota_maksimal',
        'kuota_minimal',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'date',
        'waktu_mulai' => 'datetime:H:i',
    ];

    /**
     * Mendapatkan TUK (Tempat Uji Kompetensi) yang terkait dengan jadwal.
     */
    public function tuk()
    {
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk');
    }

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

    public function asesi()
    {
        // Sintaks: return $this->belongsToMany(ModelTujuan, 'nama_tabel_pivot', 'foreign_key_model_ini', 'foreign_key_model_tujuan');

        // Sesuaikan 'asesi_jadwal', 'id_jadwal', dan 'id_asesi'
        // dengan nama tabel pivot dan kolom Anda
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_jadwal', 'id_asesi');
    }
}
