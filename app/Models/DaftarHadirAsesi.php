<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarHadirAsesi extends Model
{
    use HasFactory;

    protected $table = 'daftar_hadir_asesi';
    protected $primaryKey = 'id_daftar_hadir_asesi';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'hadir',
        'tanda_tangan_asesi',
    ];

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}
