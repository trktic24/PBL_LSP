<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunciJawabanIA05 extends Model
{
    use HasFactory;

    // Pastikan nama tabel di database kamu sesuai (biasanya lowercase + underscore)
    // Cek di phpMyAdmin kalau ragu, biasanya 'kunci_jawaban_ia05'
    protected $table = 'kunci_jawaban_ia05'; 
    
    protected $primaryKey = 'id_kunci_jawaban_ia05';

    // Kita pakai $guarded biar gampang simpan data (anti ribet fillable)
    protected $guarded = [];

    // Relasi ke Soal (Opsional, buat jaga-jaga)
    public function soal()
    {
        return $this->belongsTo(SoalIA05::class, 'id_soal_ia05', 'id_soal_ia05');
    }
}