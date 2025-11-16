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

    // Izinkan semua field dari form untuk diisi
    protected $fillable = [
        'id_asesi',
        'id_asesmen', // Foreign Key ke tabel asesmen
        'tuk_sewaktu',
        'tuk_tempatkerja',
        'tuk_mandiri',
        'ya_tidak_1',
        'ya_tidak_2',
        'ya_tidak_3',
        'alasan_banding',
        'tanggal_pengajuan_banding',
        'tanda_tangan_asesi', // <-- WAJIB DITAMBAHKAN
    ];
    
    // Konversi tipe data
    protected $casts = [
        // 'tuk_sewaktu', 'tuk_tempatkerja', 'tuk_mandiri' tetap boolean
        'tuk_sewaktu' => 'boolean',
        'tuk_tempatkerja' => 'boolean',
        'tuk_mandiri' => 'boolean',
        // Hapus cast untuk ya_tidak_1, 2, 3 karena nilainya adalah string ('Ya'/'Tidak')
        'tanggal_pengajuan_banding' => 'date',
    ];


    // --- RELASI (HUBUNGAN) ---

    /**
     * Hubungan dengan Model Asesi (Satu banding dimiliki oleh satu Asesi)
     */
    public function asesi(): BelongsTo
    {
        // Model Asesi menggunakan primary key 'id_asesi'
        // Asumsi Model Asesi sudah didefinisikan
        return $this->belongsTo(\App\Models\Asesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Hubungan dengan Model Asesmen (Satu banding terkait dengan satu hasil asesmen)
     */
    public function asesmen(): BelongsTo
    {
        // Model Asesmen menggunakan primary key 'id_asesmen'
        // Asumsi Model Asesmen sudah didefinisikan
        return $this->belongsTo(\App\Models\Asesmen::class, 'id_asesmen', 'id_asesmen');
    }
}