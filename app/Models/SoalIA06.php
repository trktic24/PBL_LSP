<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalIa06 extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit (karena tidak jamak/plural standar)
    protected $table = 'soal_ia06';

    // 2. Definisikan primary key (karena bukan 'id')
    protected $primaryKey = 'id_soal_ia06';

    // 3. Tentukan field yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'soal_ia06',
        'isi_jawaban_ia06',
        'pencapaian',
        'kunci_jawaban_ia06',
    ];

    /**
     * Relasi ke Umpan Balik
     * Satu Soal bisa memiliki banyak umpan balik (tergantung asesi)
     */
    public function umpanBalik(): HasMany
    {
        // Parameter: (Model Tujuan, Foreign Key di tabel tujuan, Local Key di tabel ini)
        return $this->hasMany(UmpanBalikIa06::class, 'id_soal_ia06', 'id_soal_ia06');
    }
}