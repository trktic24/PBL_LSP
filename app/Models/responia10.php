<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponIa10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel & primary key
    protected $table = 'respon_ia10';
    protected $primaryKey = 'id_respon_ia10';

    // Izinkan kolom-kolom ini diisi saat 'create'
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_ia10',
        'jawaban_pilihan_iya',
        'jawaban_pilihan_tidak',
        'jawaban_isian',
    ];
}