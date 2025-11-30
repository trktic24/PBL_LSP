<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaUnjukKerja extends Model
{
    use HasFactory;

    protected $table = 'master_kriteria_unjuk_kerja';
    protected $primaryKey = 'id_kriteria';

    protected $fillable = [
        'id_elemen',
        'no_kriteria', // Contoh: "1.1"
        'kriteria',    // Isi soal
        'tipe',        // 'aktivitas' atau 'demonstrasi'
        'standar_industri_kerja',
    ];

    // Helper attribute buat di Blade biar gak bingung
    // Jadi bisa panggil $kuk->pernyataan
    public function getPernyataanAttribute()
    {
        return $this->attributes['kriteria'];
    }

    public function elemen()
    {
        return $this->belongsTo(Elemen::class, 'id_elemen', 'id_elemen');
    }
}
