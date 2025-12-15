<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataSertifikasiAsesi;

class AspekIA04B extends Model
{
    use HasFactory;

    protected $table = 'aspek_ia04B';
    protected $primaryKey = 'id_aspek_ia04B';

    // Kolom yang boleh diisi
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'respon_lingkup_penyajian_proyek',
        'respon_daftar_pertanyaan',
        'respon_daftar_tanggapan', 
        'respon_kesesuaian_standar_kompetensi',
        'respon_pencapaian', 
    ];
    
    // Relasi ke DataSertifikasiAsesi
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }
}