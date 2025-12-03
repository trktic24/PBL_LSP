<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ia10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel & primary key sesuai migrasi Anda
    protected $table = 'ia10';
    protected $primaryKey = 'id_ia10';
    protected $guarded = [];

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }
}
