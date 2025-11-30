<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponDiperlukanPenyesuaianAK07 extends Model
{
    use HasFactory;

    protected $table = 'respon_diperlukan_penyesuaian_AK07';
    protected $primaryKey = 'id_diperlukan_penyesuaian_AK07';
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_persyaratan_modifikasi_AK07',
        'id_catatan_keterangan_AK07',
        'respon_penyesuaian',
        'respon_catatan_keterangan'
    ];

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function persyaratanModifikasi()
    {
        return $this->belongsTo(PersyaratanModifikasiAK07::class, 'id_persyaratan_modifikasi_AK07', 'id_persyaratan_modifikasi_AK07');
    }

    public function catatanKeterangan()
    {
        return $this->belongsTo(CatatanKeteranganAK07::class, 'id_catatan_keterangan_AK07', 'id_catatan_keterangan_AK07');
    }
}
