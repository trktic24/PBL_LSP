<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponHasilAk03 extends Model
{
    use HasFactory;
    protected $table = 'respon_hasil_ak03';
    protected $primaryKey = 'id_respon_hasil_ak03';
    
    // Pastikan field ini sesuai dengan kolom di tabel Anda
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_poin_ak03',
        'hasil',
        'catatan'
    ];
}
