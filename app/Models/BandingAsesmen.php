<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandingAsesmen extends Model
{
    use HasFactory;
    
    // Sesuaikan nama tabel jika Anda mengikuti konvensi jamak
    protected $table = 'banding_asesmen'; 

    protected $fillable = [
        'nama_asesi',
        'nama_asesor',
        'tanggal_asesmen',
        'proses_banding_dijelaskan',
        'diskusi_banding_dengan_asesor',
        'melibatkan_orang_lain',
        'skema_sertifikasi',
        'no_skema_sertifikasi',
        'alasan_banding',
        'tanda_tangan_asesi',
        'tanggal_pengajuan_banding',
    ];

    protected $casts = [
        'tanggal_asesmen' => 'date',
        'proses_banding_dijelaskan' => 'boolean',
        'diskusi_banding_dengan_asesor' => 'boolean',
        'melibatkan_orang_lain' => 'boolean',
        'tanggal_pengajuan_banding' => 'date',
    ];
}