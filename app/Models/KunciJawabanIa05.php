<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunciJawabanIA05 extends Model
{
    use HasFactory;
    
    protected $table = 'kunci_jawaban_ia05';
    protected $primaryKey = 'id_kunci_jawaban_ia05';

    protected $fillable = [
        'id_soal_ia05',
        'nomor_kunci_jawaban_ia05',
        'teks_kunci_jawaban_ia05', // <-- Ini kolom kuncinya
    ];
}