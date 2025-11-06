<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKompetensi extends Model
{
    use HasFactory;
    protected $table = 'master_unit_kompetensi'; // Nama tabel Anda
    protected $guarded = []; // Izinkan pengisian semua kolom
    public $timestamps = false; // Asumsi tabel ini tidak punya created_at/updated_at
}