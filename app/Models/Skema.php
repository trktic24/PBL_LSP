<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    use HasFactory;

    protected $table = 'skema';
    protected $primaryKey = 'id_skema';

    protected $fillable = [
        'categorie_id',
        // 'id_kelompok_pekerjaan', <-- HAPUS INI (Sudah tidak ada di tabel skema)
        'nomor_skema',
        'nama_skema',
        'deskripsi_skema',
        'harga',
        'SKKNI',
        'gambar',
    ];

    // Relasi ke atas (Category)
    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id', 'id');
    }

    // Relasi ke bawah (Kelompok Pekerjaan)
    // Skema SEKARANG punya BANYAK Kelompok Pekerjaan
    public function kelompokPekerjaan()
    {
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

    // Relasi ke List Form (Konfigurasi Formulir)
    public function listForm()
    {
        return $this->hasOne(ListForm::class, 'id_skema', 'id_skema');
    }
}