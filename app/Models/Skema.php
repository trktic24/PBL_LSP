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

    /**
     * Definisikan relasi: Satu Skema memiliki BANYAK Unit Kompetensi.
     */
    public function unitKompetensi()
    {
        // Sesuaikan nama Model & Foreign Key jika perlu
        return $this->hasMany(UnitKompetensi::class, 'id_skema', 'id_skema');
    }
}