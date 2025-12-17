<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanIa10 extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_ia10';
    protected $primaryKey = 'id_pertanyaan_ia10';
    protected $guarded = [];
    
    // Pastikan fillable ada agar bisa di-update
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_ia10',
        'pertanyaan',
        'jawaban_pilihan_iya_tidak' // 1 = Ya, 0 = Tidak
    ];
}