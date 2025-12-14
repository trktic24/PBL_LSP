<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Import Model
use App\Models\UnitKompetensi;
use App\Models\Category;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\KelompokPekerjaan;

class Skema extends Model
{
    use HasFactory;

    protected $table = 'skema';
    protected $primaryKey = 'id_skema'; // Primary key kamu 'id_skema'

    protected $fillable = [
        'category_id',
        'id_kelompok_pekerjaan',
        'nomor_skema',
        'nama_skema',
        'deskripsi_skema',
        'harga',
        'SKKNI',
        'gambar',
    ];

    /**
     * Relasi ke Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Relasi ke KelompokPekerjaan
     */
    public function kelompokPekerjaan(): HasMany
    {
        // Asumsi: Di tabel kelompok_pekerjaan, kolom FK-nya adalah 'id_skema'
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi ke Jadwal
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi ke Asesor (Many-to-Many)
     */
    public function asesor()
    {
        return $this->belongsToMany(
            Asesor::class,
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_skema',               // FK di pivot untuk model ini (Skema)
            'id_asesor'               // FK di pivot untuk model tujuan (Asesor)
        );
    }

    /**
     * Relasi ke UnitKompetensi (YANG ERROR SEBELUMNYA)
     */
    public function unitKompetensi()
    {
        return $this->hasManyThrough(
            UnitKompetensi::class,      // Model Tujuan
            KelompokPekerjaan::class,   // Model Perantara
            'id_skema',                 // FK di tabel Perantara (kelompok_pekerjaan)
            'id_kelompok_pekerjaan',    // FK di tabel Tujuan (unit_kompetensi)
            'id_skema',                 // PK di tabel Asal (skema)
            'id_kelompok_pekerjaan'     // PK di tabel Perantara (kelompok_pekerjaan)
        );
    }
}