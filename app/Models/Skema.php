<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        // Model, foreign_key, owner_key
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Relasi ke KelompokPekerjaan
     */
    public function kelompokPekerjaan(): HasMany
    {
        // LOGIC BENAR:
        // Skema punya BANYAK KelompokPekerjaan.
        // Penghubungnya adalah 'id_skema' yang ada di tabel 'kelompok_pekerjaan'.

        return $this->hasMany(
            KelompokPekerjaan::class,
            'id_skema', // Foreign Key (Kolom di tabel kelompok_pekerjaan yang nyimpen ID skema)
            'id_skema', // Local Key (Kolom ID asli di tabel skema ini)
        );
    }

    /**
     * Relasi ke konfigurasi formulir skema (FR.APL.01, FR.IA.02, dll.)
     */
    public function listForm(): HasOne
    {
        // Asumsi: Model ListForm ada di App\Models\ListForm
        // Asumsi: Foreign key di tabel 'list_form' adalah 'id_skema'
        return $this->hasOne(ListForm::class, 'id_skema', 'id_skema');
    }

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
            'Transaksi_asesor_skema', // Nama tabel pivot
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
    /**
     * Accessor untuk URL Gambar
     * Mengembalikan URL penyimpanan penuh.
     */
    public function getGambarUrlAttribute()
    {
        // Jika null, kembalikan placeholder default
        if (empty($this->gambar)) {
            return asset('images/default_pic.jpeg');
        }

        // Jika gambar disimpan di storage (default baru)
        return asset('storage/' . $this->gambar);
    }
}
