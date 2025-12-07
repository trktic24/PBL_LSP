<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasFactory;

    protected $table = 'portofolio';
    // --- FIX KRITIS ---
    // Primary key harus sesuai dengan DB Anda: 'id_portofolio'
    protected $primaryKey = 'id_portofolio'; 
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'persyaratan_dasar',
        'persyaratan_administratif',
    ];

    protected $casts = [
        'persyaratan_dasar' => 'array',
        'persyaratan_administratif' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi()
    {
        // belongsTo: foreign key di model ini ('id_data_sertifikasi_asesi') 
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Relasi ke Bukti Portofolio (IA.08/IA.09)
     */
    public function buktiPortofolioIA08IA09()
    {
        // hasMany: foreign key di model BuktiPortofolioIA08IA09 adalah 'id_portofolio'
        return $this->hasMany(BuktiPortofolioIA08IA09::class, 'id_portofolio', 'id_portofolio');
    }
}