<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class Elemen extends Model
{
    use HasFactory;

    protected $table = 'master_elemen';
    protected $primaryKey = 'id_elemen';

    protected $fillable = [
        'id_unit_kompetensi',
        'elemen',
    ];

    public function unitKompetensi()
    {
        return $this->belongsTo(UnitKompetensi::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }

    public function kriteriaUnjukKerja()
    {
        return $this->hasMany(KriteriaUnjukKerja::class, 'id_elemen', 'id_elemen');
    }
}
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Elemen extends Model
{
    // 1. WAJIB: Biar bisa nyambung ke Factory
    use HasFactory;

    // 2. WAJIB: Kasih tau nama tabelnya (karena bukan 'elemens')
    protected $table = 'master_elemen';

    // 3. WAJIB: Kasih tau nama Primary Key-nya (karena bukan 'id')
    protected $primaryKey = 'id_elemen';

    // 4. WAJIB: Biar Factory bisa ngisi semua kolom
    protected $guarded = [];

    /**
     * Relasi one-to-many (inverse):
     * Satu Elemen DIMILIKI oleh satu UnitKompetensi.
     */
    public function unitKompetensi(): BelongsTo
    {
        // Model tujuan, Foreign Key, Primary Key di tabel 'master_unit_kompetensi'
        return $this->belongsTo(UnitKompetensi::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }

    /**
     * Relasi one-to-many:
     * Satu Elemen PUNYA BANYAK KriteriaUnjukKerja
     */
    public function kriteriaUnjukKerja(): HasMany
    {
        // Model tujuan, Foreign Key, Primary Key di tabel ini
        return $this->hasMany(KriteriaUnjukKerja::class, 'id_elemen', 'id_elemen');
    }
    }
>>>>>>> Main_dev
