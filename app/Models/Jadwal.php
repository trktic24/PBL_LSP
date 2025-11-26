<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;

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
        'sesi',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pelaksanaan',
        'Status_jadwal',
        'kuota_maksimal',
        'kuota_minimal',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'date',
        'waktu_mulai' => 'datetime',
    ];

    public function tuk()
    {
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }

    public function jenisTuk()
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    /*public function asesi()
    {
        // Sintaks: return $this->belongsToMany(ModelTujuan, 'nama_tabel_pivot', 'foreign_key_model_ini', 'foreign_key_model_tujuan');

        // Sesuaikan 'asesi_jadwal', 'id_jadwal', dan 'id_asesi'
        // dengan nama tabel pivot dan kolom Anda
        return $this->belongsToMany(Asesi::class, 'data_sertifikasi_asesi', 'id_jadwal', 'id_asesi');
    }*/

    public function dataSertifikasiAsesi(): HasMany
    {
    // Jadwal (id_jadwal) memiliki banyak DataSertifikasiAsesi
    return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }
}
    