<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_pelaksanaan' => 'datetime',
    ];

    // --- Relasi ke Parent (Many-to-One) ---

    public function jenisTuk(): BelongsTo
    {
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }

    public function tuk(): BelongsTo
    {
        // Arahkan ke Model MasterTuk yang benar
        return $this->belongsTo(MasterTuk::class, 'id_tuk', 'id_tuk'); 
    }

    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }

    // --- Relasi ke Children (One-to-Many) ---

    public function dataSertifikasiAsesi(): HasMany
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_jadwal', 'id_jadwal');
    }
}