<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmpanBalikIa06 extends Model
{
    use HasFactory;

    protected $table = 'umpan_balik_ia06';
    protected $primaryKey = 'id_umpan_balik_ia06';

    protected $fillable = [
        'id_data_sertifikasi_asesi', // Hapus id_soal_ia06 dari sini
        'umpan_balik',
    ];

    /**
     * Relasi ke Data Sertifikasi Asesi
     * Asumsi Anda nanti akan membuat model DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        // Pastikan Model DataSertifikasiAsesi sudah dibuat
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}