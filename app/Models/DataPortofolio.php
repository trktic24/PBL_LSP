<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPortofolio extends Model
{
    use HasFactory;

    protected $table = 'portofolio';
    protected $primaryKey = 'id_portofolio';
    protected $guarded = [];
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'persyaratan_dasar',
        'persyaratan_administratif',
        'rekomendasi_asesor',
        'catatan_asesor',
    ];

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    public function buktiPortofolioIA08IA09()
    {
        return $this->hasMany(BuktiPortofolioIA08IA09::class, 'id_portofolio', 'id_portofolio');
    }
}
