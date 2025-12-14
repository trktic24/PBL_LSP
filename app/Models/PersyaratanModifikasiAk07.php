<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersyaratanModifikasiAK07 extends Model
{
    use HasFactory;

    protected $table = 'persyaratan_modifikasi_AK07';
    protected $primaryKey = 'id_persyaratan_modifikasi_AK07';
    protected $fillable = ['pertanyaan_karakteristik'];

    public function catatanKeterangan()
    {
        return $this->hasMany(CatatanKeteranganAK07::class, 'id_persyaratan_modifikasi_AK07', 'id_persyaratan_modifikasi_AK07');
    }
}
