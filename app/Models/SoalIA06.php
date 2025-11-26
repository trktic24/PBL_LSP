<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalIa06 extends Model
{
    protected $table = 'soal_ia06';
    protected $primaryKey = 'id_soal_ia06';
    protected $guarded = [];

    // Relasi ke tabel transaksi jawaban user
    public function lembarJawab()
    {
        // Note: Nama tabel transaksi kamu 'kunci_ia06'
        return $this->hasMany(KunciIa06::class, 'id_soal_ia06', 'id_soal_ia06');
    }
}