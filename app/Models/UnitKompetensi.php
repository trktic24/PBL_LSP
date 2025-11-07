<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKompetensi extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak jamak (plural)
    protected $table = 'master_unit_kompetensi';
    
    // Tentukan primary key jika bukan 'id'
    protected $primaryKey = 'id_unit_kompetensi';

    // Izinkan pengisian massal
    protected $guarded = [];

    // Asumsi tabel ini tidak punya timestamps (created_at/updated_at)
    // Hapus baris ini jika Anda MEMILIKI timestamps
    public $timestamps = false; 

    /**
     * Definisikan relasi inverse:
     * Satu Unit Kompetensi dimiliki oleh SATU Skema.
     */
    public function skema()
    {
        // (Foreign key di tabel ini, Primary key di tabel skema)
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }
}