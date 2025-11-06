<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKompetensi extends Model
{
    // 1. WAJIB: Biar bisa nyambung ke Factory
    use HasFactory;

    // 2. WAJIB: Kasih tau nama tabelnya (karena bukan 'unit_kompetensis')
    protected $table = 'unit_kompetensis';

    // 3. WAJIB: Kasih tau nama Primary Key-nya (karena bukan 'id')
    protected $primaryKey = 'id_unit_kompetensi';

    // 4. WAJIB: Biar Factory bisa ngisi semua kolom
    protected $guarded = [];

    /**
     * Relasi one-to-many (inverse):
     * Satu UnitKompetensi DIMILIKI oleh satu KelompokPekerjaan.
     */
    public function kelompokPekerjaan(): BelongsTo
    {
        // Model tujuan, Foreign Key, Primary Key di tabel 'kelompok_pekerjaans'
        return $this->belongsTo(KelompokPekerjaan::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }

    /**
     * Relasi one-to-many:
     * Satu UnitKompetensi PUNYA BANYAK Elemen
     */
    // public function elemens(): HasMany
    // {
    //     // Model tujuan, Foreign Key, Primary Key di tabel ini
    //     return $this->hasMany(Elemen::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    // }
}