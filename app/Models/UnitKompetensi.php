<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\KelompokPekerjaan;
use App\Models\Elemen;

class UnitKompetensi extends Model
{
    use HasFactory;

    protected $table = 'unit_kompetensi';
    protected $primaryKey = 'id_unit_kompetensi';

    protected $fillable = [
        'id_kelompok_pekerjaan',
        'urutan',
        'kode_unit',
        'judul_unit',
    ];

    // Relasi ke atas (Kelompok Pekerjaan)
    public function kelompokPekerjaan()
    {
        return $this->belongsTo(KelompokPekerjaan::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }

    /**
     * Relasi one-to-many:
     * Satu UnitKompetensi PUNYA BANYAK Elemen
     */
    public function elemen(): HasMany
    {
        // Model tujuan, Foreign Key, Primary Key di tabel ini
        return $this->hasMany(Elemen::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }    
}