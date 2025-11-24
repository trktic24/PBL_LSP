<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KunciJawabanIa05 extends Model
{
    use HasFactory;

    protected $table = 'kunci_jawaban_ia05';
    protected $primaryKey = 'id_kunci_jawaban_ia05';
    protected $guarded = [];

    // ================= RELASI =================

    /**
     * Relasi balik ke Soal.
     * Kunci jawaban ini MILIK satu Soal tertentu.
     */
    public function soal()
    {
        // belongsTo(RelatedModel, foreign_key_di_tabel_ini, owner_key_di_tabel_induk)
        return $this->belongsTo(SoalIa05::class, 'id_soal_ia05', 'id_soal_ia05');
    }
}