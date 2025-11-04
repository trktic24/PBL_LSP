<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asesi; // <-- Pastikan Model Asesi di-import

class TandaTanganPemohon extends Model
{
    use HasFactory;
    
    // Opsional: Laravel secara default mencari tabel 'tanda_tangan_pemohons'
    // protected $table = 'tanda_tangan_pemohons'; 

    /**
     * Kolom yang diizinkan untuk diisi (mass assignable) dari form.
     */
    protected $fillable = [
        'id_asesi',             // Kunci asing yang menandakan siapa yang bertanda tangan
        'data_tanda_tangan',    // Kolom LONGTEXT untuk menyimpan data Base64
    ];

    /**
     * Relasi ke Model Asesi (Foreign Key: id_asesi).
     * Model ini dimiliki oleh satu Asesi.
     */
    public function asesi()
    {
        // Sesuaikan dengan nama Model Asesi Anda dan Primary Key-nya (id_asesi)
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}