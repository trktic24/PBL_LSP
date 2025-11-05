<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JenisTuk; // Tambahkan
use App\Models\Tuk; // Tambahkan
use App\Models\Skema; // Sudah ada
use App\Models\Asesor; // Tambahkan

class Jadwal extends Model
{
    protected $table = 'jadwal';

    // Kolom-kolom yang boleh diisi secara massal (PENTING untuk keamanan)
    protected $fillable = [
        'id_jenis_tuk',
        'id_tuk',
        'id_skema',
        'id_asesor',
        // Tambahkan kolom lain di tabel 'jadwal' seperti 'tanggal_mulai', 'tanggal_selesai', dll.
    ];

    // Relasi ke tabel 'skema' (Sudah benar)
    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema');
    }
    
    // Relasi ke tabel 'jenis_tuk'
    public function jenisTuk()
    {
        // Menggunakan foreign key 'id_jenis_tuk'
        return $this->belongsTo(JenisTuk::class, 'id_jenis_tuk');
    }
    
    // Relasi ke tabel 'tuk'
    public function tuk()
    {
        // Menggunakan foreign key 'id_tuk'
        return $this->belongsTo(Tuk::class, 'id_tuk');
    }
    
    // Relasi ke tabel 'asesor'
    public function asesor()
    {
        // Menggunakan foreign key 'id_asesor'
        return $this->belongsTo(Asesor::class, 'id_asesor');
    }
}