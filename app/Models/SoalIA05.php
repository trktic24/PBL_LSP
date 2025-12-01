<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalIA05 extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan nama tabel di screenshot
    protected $table = 'soal_ia05'; 
    
    // Sesuaikan dengan Primary Key di screenshot
    protected $primaryKey = 'id_soal_ia05'; 

    // Sesuaikan dengan nama kolom di screenshot
    protected $fillable = [
        'soal_ia05',
        'opsi_jawaban_a',
        'opsi_jawaban_b',
        'opsi_jawaban_c',
        'opsi_jawaban_d',
    ];
}