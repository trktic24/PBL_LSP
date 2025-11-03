<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    use HasFactory;
    protected $table = 'master_skema'; // Nama tabel Anda
    protected $primaryKey = 'id_skema'; // Asumsi nama primary key
    protected $guarded = []; // Izinkan pengisian semua kolom
}