<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrMapa01 extends Model
{
    use HasFactory;
    
    protected $table = 'fr_mapa01s';
    protected $guarded = ['id'];

    // Ubah JSON DB jadi Array PHP otomatis
    protected $casts = [
        'pendekatan_asesmen' => 'array',
        'konteks_lingkungan' => 'array',
        'peluang_bukti' => 'array',
        'pelaksana_asesmen' => 'array',
        'konfirmasi_relevan' => 'array',
        'standar_industri' => 'array',
        'kelompok_pekerjaan' => 'array',
        'unit_kompetensi' => 'array', // Ini yang paling penting (Tabel checklist besar)
        'konfirmasi_orang_relevan_data' => 'array',
        'penyusun' => 'array',
        'validator' => 'array',
        'karakteristik_ada_checkbox' => 'boolean',
        // ... boolean lainnya
    ];

    // Relasi balik ke Sertifikasi
    public function sertifikasi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }
}