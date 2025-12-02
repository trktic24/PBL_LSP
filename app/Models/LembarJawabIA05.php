<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LembarJawabIa05 extends Model
{
    protected $table = 'lembar_jawab_ia05';
    protected $primaryKey = 'id_lembar_jawab_ia05';
    protected $guarded = [];

    // ================= RELASI =================

    /**
     * Relasi ke Peserta (Data Sertifikasi Asesi).
     * Lembar jawab ini MILIK siapa?
     */
    public function dataSertifikasiAsesi()
    {
        // Pastikan nama model induk dan foreign key-nya benar
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Relasi ke Master Soal yang sedang dijawab.
     * Ini penting untuk mengambil teks pertanyaan dan opsi jawaban.
     */
    public function soal()
    {
        return $this->belongsTo(SoalIa05::class, 'id_soal_ia05', 'id_soal_ia05');
    }
}