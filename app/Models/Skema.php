<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

// [PERBAIKAN] Pastikan KETIGA baris ini ada
use App\Models\UnitKompetensi;
use App\Models\KelompokPekerjaan;
use App\Models\DetailSertifikasi; // <-- Ini yang menyebabkan error Anda saat ini


class Skema extends Model
{
    use HasFactory;
    protected $table = 'skema';
    protected $primaryKey = 'id_skema';
    protected $guarded = [];

    protected $fillable = [
        'kode_unit', 'nama_skema', 'deskripsi_skema', 'SKKNI', 'gambar'
    ];

    // --- FUNGSI RELASI ---

    /**
     * Relasi HasManyThrough: Skema -> KelompokPekerjaan -> UnitKompetensi
     */
    public function unitKompetensi(): HasManyThrough
    {
        return $this->hasManyThrough(
            UnitKompetensi::class,      // 1. Model tujuan
            KelompokPekerjaan::class,   // 2. Model perantara
            'id_skema',                 // 3. Foreign key di tabel perantara ('kelompok_pekerjaans')
            'id_kelompok_pekerjaan',    // 4. Foreign key di tabel tujuan ('master_unit_kompetensi')
            'id_skema',                 // 5. Local key di tabel ini ('skema')
            'id_kelompok_pekerjaan'     // 6. Local key di tabel perantara ('kelompok_pekerjaans')
        );
    }

    /**
     * Relasi: 1 Skema punya BANYAK KelompokPekerjaan
     */
    public function kelompokPekerjaans(): HasMany
    {
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi: 1 Skema punya BANYAK DetailSertifikasi
     */
    public function detailSertifikasi(): HasMany
    {
        return $this->hasMany(DetailSertifikasi::class, 'id_skema', 'id_skema');
    }
}