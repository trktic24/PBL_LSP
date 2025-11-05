<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalIA06 extends Model
{
    use HasFactory;

    // Tentukan nama tabel & primary key
    protected $table = 'soal_ia06';
    protected $primaryKey = 'id_soal_ia06';

    /**
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'soal_ia06',
        'kunci_jawaban_ia06' // Kunci jawaban master sekarang ada di sini
    ];

    /**
     * Relasi "Satu Soal" memiliki "Banyak Jawaban Asesi".
     * Kita beri nama relasinya 'jawabanAsesi' agar jelas,
     * meskipun modelnya bernama KunciIA06.
     */
    public function jawabanAsesi()
    {
        // Model KunciIA06 adalah tabel 'kunci_ia06'
        return $this->hasMany(KunciIA06::class, 'id_soal_ia06', 'id_soal_ia06');
    }
}