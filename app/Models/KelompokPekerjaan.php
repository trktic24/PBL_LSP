<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelompokPekerjaan extends Model
{
    // 1. WAJIB: Biar bisa nyambung ke Factory
    use HasFactory;

    // 2. WAJIB: Kasih tau nama Primary Key-nya (karena bukan 'id')
    protected $primaryKey = 'id_kelompok_pekerjaan';

    // 3. WAJIB: Biar Factory bisa ngisi semua kolom
    protected $guarded = [];

    /**
     * Relasi one-to-many (inverse):
     * Satu KelompokPekerjaan DIMILIKI oleh satu Skema.
     */
    public function skema(): BelongsTo
    {
        // Model tujuan, Foreign Key, Primary Key di tabel 'skema'
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi one-to-many:
     * Satu KelompokPekerjaan PUNYA BANYAK UnitKompetensi
     */
    public function unitKompetensis(): HasMany
    {
        // Model tujuan, Foreign Key, Primary Key di tabel ini
        return $this->hasMany(UnitKompetensi::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }
}