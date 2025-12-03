<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalIa06 extends Model
{
    use HasFactory;

    // Nama tabel spesifik
    protected $table = 'soal_ia06';

    // Primary key custom
    protected $primaryKey = 'id_soal_ia06';

    // Kolom yang boleh diisi
    protected $fillable = [
        'soal_ia06',
        'kunci_jawaban_ia06',
    ];

    /**
     * Relasi: Satu soal bisa memiliki banyak jawaban (dari banyak asesi)
     */
    public function jawabans(): HasMany
    {
        return $this->hasMany(JawabanIa06::class, 'id_soal_ia06', 'id_soal_ia06');
    }
}