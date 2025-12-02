<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunciIa06 extends Model
{
    // Nama tabel sesuai migrasi kamu yang terakhir
    protected $table = 'kunci_ia06'; 
    protected $primaryKey = 'id_kunci_ia06';
    protected $guarded = [];

    // Relasi ke Master Soal (untuk ambil teks pertanyaan)
    public function soal()
    {
        return $this->belongsTo(SoalIa06::class, 'id_soal_ia06', 'id_soal_ia06');
    }

    // Relasi ke Data Sertifikasi Asesi
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}