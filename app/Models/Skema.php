<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skema extends Model
{
    // 1. WAJIB: Biar bisa nyambung ke SkemaFactory
    use HasFactory;

    // 2. WAJIB: Kasih tau nama tabelnya (karena bukan 'skemas')
    protected $table = 'skema';

    // 3. WAJIB: Kasih tau nama Primary Key-nya (karena bukan 'id')
    protected $primaryKey = 'id_skema';

    // 4. WAJIB: Biar Factory bisa ngisi semua kolom
    protected $guarded = [];

    /**
     * Relasi: 1 Skema punya BANYAK KelompokPekerjaan
     */
    // public function kelompokPekerjaans(): HasMany
    // {
    //     // Model tujuan, Foreign Key, Local Key (PK di tabel ini)
    //     return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    // }
}