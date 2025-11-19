<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiDasar extends Model
{
    use HasFactory;

    protected $table = 'bukti_dasar';
    protected $primaryKey = 'id_bukti_dasar';

    protected $fillable = [
        'id_data_sertifikasi_asesi', 
        'status_kelengkapan', 
        'bukti_dasar', 
        'status_validasi',
        'keterangan' 
    ];

    protected $guarded = [];

    /**
     * Relasi ke Data Sertifikasi Asesi
     */
    public function dataSertifikasi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}