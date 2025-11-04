<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk merekam Pengajuan Banding.
 * Terkait dengan Asesmen yang dibandig (Model Asesmen).
 */
class Banding extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'banding';
    // Primary key untuk Model ini
    protected $primaryKey = 'id_banding';

    // Izinkan semua field dari form untuk diisi (pastikan migration sudah dibuat)
    protected $fillable = [
        'id_asesi',
        'id_asesmen', // Menggunakan 'id_asesmen' sebagai Foreign Key ke tabel asesmen
        'tuk_sewaktu',
        'tuk_tempatkerja',
        'tuk_mandiri',
        'ya_tidak_1',
        'ya_tidak_2',
        'ya_tidak_3',
        'alasan_banding',
        'tanggal_pengajuan_banding',
    ];
    
    // Konversi tipe data
    protected $casts = [
        'tuk_sewaktu' => 'boolean',
        'tuk_tempatkerja' => 'boolean',
        'tuk_mandiri' => 'boolean',
        'tanggal_pengajuan_banding' => 'date',
    ];


    // --- RELASI (HUBUNGAN) ---

    /**
     * Hubungan dengan Model Asesi (Satu banding dimiliki oleh satu Asesi)
     */
    public function asesi(): BelongsTo
    {
        // Model Asesi menggunakan primary key 'id_asesi'
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Hubungan dengan Model Asesmen (Satu banding terkait dengan satu hasil asesmen)
     */
    public function asesmen(): BelongsTo
    {
        // Model Asesmen menggunakan primary key 'id_asesmen'
        return $this->belongsTo(Asesmen::class, 'id_asesmen', 'id_asesmen');
    }
}