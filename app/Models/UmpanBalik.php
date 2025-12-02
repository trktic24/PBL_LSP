<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpanBalik extends Model
{
    use HasFactory;

    protected $table = 'respon_ak03';
    protected $primaryKey = 'id_umpan_balik';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'penjelasan_proses_asesmen',
        'memahami_standar_kompetensi',
        'diskusi_metode_dengan_asesor',
        'menggali_bukti_pendukung',
        'kesempatan_demos_kompetensi',
        'penjelasan_keputusan_asesmen',
        'umpan_balik_setelah_asesmen',
        'mempelajari_dokumen_asesmen',
        'jaminan_kerahasiaan',
        'komunikasi_efektif_asesor',
        'catatan',
    ];

    /**
     * Relasi ke tabel data_sertifikasi_asesi (FK)
     */
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}