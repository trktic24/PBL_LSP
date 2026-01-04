<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoalIA05 extends Model
{
    use HasFactory;

    // Definisi tabel dan primary key (WAJIB)
    protected $table = 'soal_ia05';
    protected $primaryKey = 'id_soal_ia05';
    protected $guarded = [];
    // Sesuaikan dengan nama kolom di screenshot
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'soal_ia05',
        'opsi_a_ia05',
        'opsi_b_ia05',
        'opsi_c_ia05',
        'opsi_d_ia05',
    ];

    // ================= RELASI =================

    /**
     * Relasi ke Kunci Jawaban (One-to-One).
     * Setiap soal master MEMILIKI SATU kunci jawaban di tabel terpisah.
     */
    public function kunciJawaban()
    {
        // hasOne(RelatedModel, foreign_key_di_tabel_terkait, local_key_di_tabel_ini)
        return $this->hasOne(KunciJawabanIA05::class, 'id_soal_ia05', 'id_soal_ia05');
    }

    /**
     * Relasi ke Lembar Jawab (One-to-Many).
     * Satu soal master bisa muncul di BANYAK lembar jawab (dipakai banyak peserta).
     */
    public function lembarJawab()
    {
        return $this->hasMany(LembarJawabIA05::class, 'id_soal_ia05', 'id_soal_ia05');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    
}