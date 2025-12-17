<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPortofolio extends Model
{
    use HasFactory;

    protected $table = 'data_portofolio';
    protected $primaryKey = 'id_portofolio_asesi';
    protected $guarded = [];

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}