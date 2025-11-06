<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model untuk merekam data Asesmen (Hasil Akhir Asesmen).
 * Model ini diperlukan agar BandingAsesmenController bisa membaca data asesmen.
 */
class Asesmen extends Model
{
    use HasFactory;

    // Nama tabel di database Anda
    protected $table = 'asesmen';
    // Primary key untuk Model ini
    protected $primaryKey = 'id_asesmen';
    
    // Izinkan mass assignment (sesuaikan dengan kebutuhan Anda)
    protected $guarded = [];

    // Relasi ke Model Asesi (Asesmen ini dimiliki oleh satu Asesi)
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    // Relasi ke Model Asesor (Jika ada FK id_asesor di tabel asesmen)
    /*
    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id_asesor');
    }
    */

    // Relasi ke Model Banding (Satu Asesmen bisa memiliki satu pengajuan Banding)
    public function banding(): HasOne
    {
        return $this->hasOne(Banding::class, 'id_asesmen', 'id_asesmen');
    }
}