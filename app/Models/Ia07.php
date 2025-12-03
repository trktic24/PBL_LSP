<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ia07 extends Model
{
    use HasFactory;

    protected $table = 'ia07';
    protected $primaryKey = 'id_ia07';
    protected $guarded = [];

    /**
     * Relasi ke Data Sertifikasi (Parent)
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Relasi ke Master Pertanyaan (Untuk mengambil teks soal)
     * DISESUAIKAN: Menggunakan model PertanyaanLisan
     */
    public function pertanyaanLisan(): BelongsTo
    {
        return $this->belongsTo(PertanyaanLisan::class, 'id_pertanyaan_lisan', 'id_pertanyaan_lisan');
    }
}