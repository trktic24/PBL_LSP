<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponPotensiAk07 extends Model
{
    use HasFactory;

    protected $table = 'respon_potensi_AK07';
    protected $primaryKey = 'id_respon_potensi_AK07';
    
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_poin_potensi_AK07',
        'respon_asesor'
    ];

    // Relasi ke Induk
    public function dataSertifikasi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}