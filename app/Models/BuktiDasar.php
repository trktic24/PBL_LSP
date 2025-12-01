<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiDasar extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan nama tabel dan primary key
    protected $table = 'bukti_dasar';
    protected $primaryKey = 'id_bukti_dasar';
    
    // Kolom yang boleh diisi (fillable)
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'status_kelengkapan', // Default: 'tidak_ada'
        'bukti_dasar', // Untuk menyimpan nama file
        'status_validasi', // Default: false (0)
    ];

    // Opsional: Relasi ke DataSertifikasiAsesi jika ada
    // public function dataSertifikasiAsesi()
    // {
    //     return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    // }
}