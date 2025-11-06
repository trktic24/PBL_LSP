<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// (Kita mungkin perlu menambahkan 'use' untuk model lain, tapi ini sudah cukup)

class Skema extends Model
{
    // 1. WAJIB: Biar bisa nyambung ke SkemaFactory
    use HasFactory;

    // 2. WAJIB: Kasih tau nama tabelnya (karena bukan 'skemas')
    protected $table = 'skema';

    // 3. WAJIB: Kasih tau nama Primary Key-nya (karena bukan 'id')
    protected $primaryKey = 'id_skema';

    // 4. WAJIB: Biar Factory bisa ngisi semua kolom (Hanya satu)
    protected $guarded = [];

    // --- SEMUA FUNGSI RELASI HARUS ADA DI DALAM SINI ---

    /**
     * Relasi: 1 Skema punya BANYAK UnitKompetensi (untuk SKKNI)
     */
    public function unitKompetensi()
    {
        // 'id_skema' (foreign key), 'id_skema' (local key)
        return $this->hasMany(UnitKompetensi::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi: 1 Skema punya BANYAK DetailSertifikasi
     */
    public function detailSertifikasi()
    {
        return $this->hasMany(DetailSertifikasi::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi: 1 Skema punya BANYAK KelompokPekerjaan
     */
    public function kelompokPekerjaans(): HasMany
    {
        // Model tujuan, Foreign Key, Local Key (PK di tabel ini)
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

} // <-- INI ADALAH PENUTUP CLASS YANG BENAR (Hanya satu)