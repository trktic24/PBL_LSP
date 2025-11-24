<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoalIa05 extends Model
{
    use HasFactory;

    // Nama tabel dan Primary Key yang custom wajib didefinisikan
    protected $table = 'soal_ia05';
    protected $primaryKey = 'id_soal_ia05';

    // Biar gampang mass assignment (create/update)
    protected $guarded = [];

    // ================= RELASI =================

    /**
     * Relasi ke Skema (Induk).
     * Setiap soal dimiliki oleh satu Skema.
     */
    public function skema()
    {
        // belongsTo(RelatedModel, foreign_key_di_tabel_ini, owner_key_di_tabel_induk)
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi ke Kunci Jawaban (One-to-One).
     * Setiap soal MEMILIKI SATU kunci jawaban.
     */
    public function kunciJawaban()
    {
        // hasOne(RelatedModel, foreign_key_di_tabel_terkait, local_key_di_tabel_ini)
        // Perhatikan urutan parameternya berbeda dengan belongsTo
        return $this->hasOne(KunciJawabanIa05::class, 'id_soal_ia05', 'id_soal_ia05');
    }

    /**
     * Relasi ke Lembar Jawab (One-to-Many).
     * Satu soal bisa muncul di banyak lembar jawab (dikerjakan banyak peserta).
     * (Opsional, tapi bagus untuk dimiliki)
     */
    public function lembarJawab()
    {
        return $this->hasMany(LembarJawabIa05::class, 'id_soal_ia05', 'id_soal_ia05');
    }
}