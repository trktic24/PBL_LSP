<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanKeteranganAK07 extends Model
{
    use HasFactory;

    protected $table = 'catatan_keterangan_AK07';
    protected $primaryKey = 'id_catatan_keterangan_AK07';
    protected $fillable = ['id_persyaratan_modifikasi_AK07', 'isi_opsi'];

    public function persyaratanModifikasi()
    {
        return $this->belongsTo(PersyaratanModifikasiAK07::class, 'id_persyaratan_modifikasi_AK07', 'id_persyaratan_modifikasi_AK07');
    }
}
