<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponPotensiAK07 extends Model
{
    use HasFactory;

    protected $table = 'respon_potensi_AK07';
    protected $primaryKey = 'id_respon_potensi_AK07';
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_poin_potensi_AK07',
        'respon_asesor'
    ];

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function poinPotensi()
    {
        return $this->belongsTo(PoinPotensiAK07::class, 'id_poin_potensi_AK07', 'id_poin_potensi_AK07');
    }
}
