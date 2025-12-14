<?php

namespace App\Models;

use App\Models\Asesor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Perbaikan typo 'belongsTo' jadi 'BelongsTo'
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
        'waktu_mulai',
        'waktu_selesai', // [TAMBAHAN 1] Masukkan ke fillable
        'Status_jadwal',
        'kuota_maksimal',
        'kuota_minimal',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime', // [TAMBAHAN 2] Masukkan ke casts
    ];

    // --- CUSTOM ACCESSORS ---

    public function getTanggalMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getTanggalSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getTanggalPelaksanaanAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
    
    public function getWaktuMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    // [TAMBAHAN 3] Accessor untuk Waktu Selesai
    public function getWaktuSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    // --- RELASI ---

    public function jenisTuk(): BelongsTo
    {
        return $this->belongsTo(JenisTUK::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    public function masterTuk(): BelongsTo
    {
        return $this->belongsTo(MasterTUK::class, 'id_tuk', 'id_tuk'); 
    }

    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function dataSertifikasiAsesi(): HasMany
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }

    public function asesi(): HasMany
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }
}