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
     * Lembar jawab ini milik siapa?
     */
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Relasi ke Soal yang sedang dijawab.
     * Ini penting untuk mengambil teks soalnya nanti di Controller/View.
     */
    public function soal()
    {
        return $this->belongsTo(SoalIa05::class, 'id_soal_ia05', 'id_soal_ia05');
    }
}