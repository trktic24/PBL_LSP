<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembarJawabIA05 extends Model
{
    use HasFactory;
    
    protected $table = 'lembar_jawab_ia05';
    
    // PERHATIKAN: Primary key Anda di screenshot adalah 'id_lembar_jawab_ia06'
    // Ini sepertinya typo di database Anda, tapi Model harus mengikutinya.
    protected $primaryKey = 'id_lembar_jawab_ia06'; 

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_soal_ia05',
        'id_kunci_jawaban_ia05', // <-- Ada di screenshot
        'teks_jawaban_asesi_ia05', // <-- Ini kolom jawaban asesi
        'pencapaian_ia05_iya', // <-- Ini untuk penilaian
        'pencapaian_ia05_tidak', // <-- Ini untuk penilaian
    ];
}