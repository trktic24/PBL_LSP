<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResponIA04A;
use App\Models\DataSertifikasiAsesi; // Tambahkan ini

class PoinIA04A extends Model
{
    use HasFactory;

    protected $table = 'poin_ia04A';
    protected $primaryKey = 'id_poin_ia04A';

    // TAMBAHKAN FILLABLE
    protected $fillable = [
        'id_data_sertifikasi_asesi', // <--- PENTING: Foreign Key
        'hal_yang_disiapkan',
        'waktu_disiapkan_menit',
        'hal_yang_didemonstrasikan', // Asesor input
        'waktu_demonstrasi_menit',
    ];

    /**
     * Relasi: Many-to-One ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi()
    {
        // Asumsi kolom FK adalah 'id_data_sertifikasi_asesi'
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }

    // ... (fungsi respons() jika digunakan, tapi tidak diperlukan untuk logika ini)
}