<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunciIA06 extends Model
{
    use HasFactory;

    // Tentukan nama tabel & primary key
    protected $table = 'kunci_ia06';
    protected $primaryKey = 'id_kunci_ia06';

    /**
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'id_soal_ia06',
        'id_data_sertifikasi_asesi',
        'teks_jawaban_ia06' // Ini adalah kolom untuk jawaban teks dari asesi
    ];

    /**
     * Relasi "Jawaban ini" dimiliki oleh "Satu Soal".
     */
    public function soal()
    {
        return $this->belongsTo(SoalIA06::class, 'id_soal_ia06', 'id_soal_ia06');
    }

    /**
     * Relasi "Jawaban ini" dimiliki oleh "Satu Asesi".
     * (Asumsi nama model Anda adalah DataSertifikasiAsesi)
     */
    public function asesi()
    {
        // Ganti 'App\Models\DataSertifikasiAsesi' jika path model Anda berbeda
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}