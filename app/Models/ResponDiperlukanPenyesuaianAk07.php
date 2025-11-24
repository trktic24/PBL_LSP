<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponDiperlukanPenyesuaianAk07 extends Model
{
    use HasFactory;

    protected $table = 'respon_diperlukan_penyesuaian_AK07';
    protected $primaryKey = 'id_diperlukan_penyesuaian_AK07';
    
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_persyaratan_modifikasi_AK07',
        'id_catatan_keterangan_AK07', // Bisa null
        'respon_penyesuaian', // Enum: 'Ya', 'Tidak'
        'respon_catatan_keterangan' // Teks manual
    ];

    // Relasi ke Induk
    public function dataSertifikasi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}