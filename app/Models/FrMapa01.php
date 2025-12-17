<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrMapa01 extends Model
{
    use HasFactory;

    protected $table = 'fr_mapa01s';
    
    // Pastikan ini array
    protected $guarded = ['id']; 

    // Konversi Checkbox ke JSON
    protected $casts = [
    // Bagian 1: Pendekatan & Konteks
    'pendekatan_asesmen' => 'array',
    'konteks_lingkungan' => 'array',
    'peluang_bukti' => 'array',
    'pelaksana_asesmen' => 'array',
    'konfirmasi_relevan' => 'array',
    
    // Bagian 2: Tabel Unit Kompetensi (Array multidimensi)
    'kelompok1' => 'array', // Untuk tabel kelompok pekerjaan
    'unit_kompetensi' => 'array', // Untuk tabel checklist besar
    
    // Bagian 3: Modifikasi (Checkbox aktif/tidak)
    'karakteristik_ada_checkbox' => 'boolean',
    'kebutuhan_kontekstualisasi_checkbox' => 'boolean',
    'saran_paket_checkbox' => 'boolean',
    'penyesuaian_perangkat_checkbox' => 'boolean',
    
    // Bagian Tanda Tangan
    'konfirmasi_nama' => 'array',
    'konfirmasi_ttd_tanggal' => 'array',
    'penyusun' => 'array',
    'validator' => 'array',
];
}