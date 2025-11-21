<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponBuktiAk01 extends Model
{
    use HasFactory;

    protected $table = 'respon_bukti_ak01'; // Nama tabel sesuai migrasi
    protected $primaryKey = 'id_respon_bukti_ak01'; // Primary key
    protected $guarded = []; // Biar bisa create massal

    /**
     * Relasi ke Master Bukti (untuk ambil nama buktinya nanti)
     */
    public function buktiMaster()
    {
        // belongsTo(ModelTujuan, Foreign_Key_Di_Sini, Primary_Key_Tujuan)
        return $this->belongsTo(BuktiAk01::class, 'id_bukti_ak01', 'id_bukti_ak01');
    }

    /**
     * Relasi ke Data Sertifikasi (biar tau ini punya siapa)
     */
    public function dataSertifikasi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}
