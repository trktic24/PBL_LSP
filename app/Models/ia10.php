<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ia10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak jamak
    protected $table = 'IA_10';

    // Izinkan semua field ini diisi
    protected $guarded = [];
}
