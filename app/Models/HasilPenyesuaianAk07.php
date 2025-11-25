<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPenyesuaianAk07 extends Model
{
    use HasFactory;

    protected $table = 'hasil_penyesuaian_AK07';
    protected $primaryKey = 'id_hasil_penyesuaian_AK07';
    
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'Acuan_Pembanding_Asesmen',
        'Metode_Asesmen',
        'Instrumen_Asesmen'
    ];
}