<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     * (Sesuai dengan file migrasi Anda)
     */
    protected $table = 'skema'; // <-- DISESUAIKAN

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_skema';

    /**
     * Izinkan semua kolom diisi.
     */
    protected $guarded = [];
    public function unitkompetensi()
    {
         return $this->hasMany(UnitKompetensi::class, 'id_kelompok_pekerjaan', 'id_skema'); 
        // tapi logikanya harus sesuai ERD, biasanya ini kurang tepat
    }
    // 🟢 Tambahan baru: fillable (opsional, untuk keamanan CRUD)
    protected $fillable = [
        'nama_skema',
        'deskripsi_skema',
        'gambar',
        'SKKNI',
        'kode_unit',
    ];
}