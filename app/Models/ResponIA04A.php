<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Penting: Pastikan Anda mengimpor model lain yang menjadi Foreign Key
use App\Models\PoinIA04A; 
// Asumsi Anda memiliki model ini untuk data asesi
use App\Models\DataSertifikasiAsesi; 

class ResponIA04A extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'respon_ia04A';

    // Primary Key tabel
    protected $primaryKey = 'id_respon_ia04A';

    // Kolom yang dapat diisi massal (Mass Assignable)
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_poin_ia04A',
        'respon_poin_ia04A',
        'umpan_balik_untuk_asesi',
        'ttd_supervisor',
    ];

    /**
     * Definisi Relasi: Many-to-One ke PoinIA04A
     * Satu ResponIA04A dimiliki oleh satu PoinIA04A (Relasi Utama)
     */
    public function poin()
    {
        // Parameter: Nama Model, Foreign Key di tabel ini, Primary Key di tabel target
        return $this->belongsTo(PoinIA04A::class, 'id_poin_ia04A', 'id_poin_ia04A');
    }

    /**
     * Definisi Relasi: Many-to-One ke DataSertifikasiAsesi
     * Satu ResponIA04A dimiliki oleh satu DataSertifikasiAsesi
     */
    public function dataAsesi()
    {
        // Ganti 'DataSertifikasiAsesi' dengan nama model yang benar jika berbeda.
        // Asumsi Primary Key di tabel 'data_sertifikasi_asesi' adalah 'id'.
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id');
    }
}