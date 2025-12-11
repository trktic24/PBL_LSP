<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPortofolio extends Model
{
    use HasFactory;

    // PASTIKAN NAMA TABEL SESUAI DENGAN MIGRATION
    protected $table = 'portofolio'; 
    protected $primaryKey = 'id_portofolio'; // Sesuai migration: $table->id('id_portofolio');
    
    // Kolom yang dapat diisi massal
    protected $fillable = [ // Gunakan fillable, bukan guarded
        'id_data_sertifikasi_asesi',
        'persyaratan_dasar',
        'persyaratan_administratif',
    ]; 
    
    // Hapus baris protected $guarded = []; atau pastikan $guarded = []
    protected $guarded = []; // Baris ini sudah benar jika fillable tidak digunakan

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi()
    {
        // Ganti foreign key jika perlu, sesuaikan dengan migration
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}