<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponApl02Ia01 extends Model
{
    use HasFactory;

    protected $table = 'respon_apl02_ia01';
    protected $primaryKey = 'id_respon_apl02';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_kriteria',
        'respon_asesi_apl02', // Boolean (0/1)
        'bukti_asesi_apl02',
        'pencapaian_ia01',    // Boolean (0/1) -> Hasil IA.01
        'penilaian_lanjut_ia01', // Text area
    ];

    // Relasi balik ke Kriteria (Soal)
    public function kriteria()
    {
        return $this->belongsTo(KriteriaUnjukKerja::class, 'id_kriteria', 'id_kriteria');
    }
}
