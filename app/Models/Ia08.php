<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ia08 extends Model
{
    use HasFactory;

    protected $table = 'ia08';
    protected $primaryKey = 'id_ia08';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'bukti_tambahan',
        'rekomendasi',
    ];

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}