<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PertanyaanLisan extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar sesuai migration Anda
    protected $table = 'master_pertanyaan_lisan';
    
    // Primary Key sesuai migration
    protected $primaryKey = 'id_pertanyaan_lisan';
    
    protected $guarded = [];

    /**
     * Relasi ke Unit Kompetensi
     */
    public function unitKompetensi(): BelongsTo
    {
        return $this->belongsTo(UnitKompetensi::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }
}