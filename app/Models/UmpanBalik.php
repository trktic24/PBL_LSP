<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmpanBalik extends Model
{
    use HasFactory;

    protected $table = 'respon_ak04';
    protected $primaryKey = 'id_respon_ak04';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'penjelasan_banding',
        'diskusi_dengan_asesor',
        'melibatkan_orang_lain',
        'alasan_banding',
    ];

    /**
     * Relasi ke tabel data_sertifikasi_asesi (FK)
     */
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}