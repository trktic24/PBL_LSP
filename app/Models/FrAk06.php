<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrAk06 extends Model
{
    use HasFactory;

    protected $table = 'fr_ak06s';

    // Izinkan semua kolom diisi
    protected $guarded = ['id'];
    protected $casts = [
    'tinjauan' => 'array', // Untuk tabel prinsip asesmen
    'dimensi' => 'array',  // Untuk tabel dimensi kompetensi
    'peninjau' => 'array', // Untuk data peninjau
    ];
}