<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarHadir extends Model
{
    protected $table = 'daftar_hadir_asesi';
    protected $primaryKey = 'id_daftar_hadir_asesi';

    protected $fillable = [
        'tanda_tangan_asesi',
        'id_data_sertifikasi_asesi'
    ];
    //protected $guarded = [];

public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}