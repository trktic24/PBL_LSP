<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// [PERBAIKAN] Model yang di-use sudah dirapikan
use App\Models\Jadwal;
use App\Models\Category; // <-- [PENTING] Ganti dari 'categorie' ke 'category'
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi; // <-- [ASUMSI] Nama modelmu ini
use App\Models\Asesor;
use App\Models\AsesorSkemaTransaksi;

class Skema extends Model
{
    use HasFactory;
    
    protected $table = 'skema';
    protected $primaryKey = 'id_skema';

    /**
     * [PERBAIKAN]
     * Pilih salah satu: $fillable atau $guarded.
     * $guarded = [] artinya semua kolom boleh diisi (mass assignable).
     * Ini lebih simpel daripada nulis $fillable satu per satu.
     */
    protected $guarded = [];

    // [DIHAPUS] $fillable tidak perlu jika sudah pakai $guarded = []
    // protected $fillable = [
    //     'kode_unit', 'nama_skema', 'deskripsi_skema', 'SKKNI', 'gambar'
    // ];


    // --- FUNGSI RELASI ---

    /**
     * Relasi: 1 Skema punya BANYAK Jadwal
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_skema', 'id_skema');
    }

    /**
     * Relasi: 1 Skema punya BANYAK KelompokPekerjaan
     */
    public function kelompokPekerjaan(): HasMany
    {
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

    /**
     * [PERBAIKAN]
     * Relasi: 1 Skema DIMILIKI OLEH 1 Category
     */
    public function category(): BelongsTo // <-- Nama fungsi dibenerin (singular)
    {
        // Relasi BelongsTo: (Model, foreign_key, owner_key)
        // 'categorie_id' -> FK di tabel 'skema' (sesuai migrasi typo-mu)
        // 'id'           -> PK di tabel 'categories'
        return $this->belongsTo(Category::class, 'categorie_id', 'id');
    }
    
    /**
     * Relasi "Jalan Pintas" (HasManyThrough)
     * Skema -> (lewat KelompokPekerjaan) -> UnitKompetensi
     */
    public function unitKompetensi()
    {
        return $this->hasManyThrough(
            UnitKompetensi::class,      // 1. Model Tujuan (Unit)
            KelompokPekerjaan::class,   // 2. Model Perantara (Kelompok)
            'id_skema',                 // 3. FK di tabel perantara (kelompok_pekerjaan)
            'id_kelompok_pekerjaan',    // 4. FK di tabel tujuan (unit_kompetensi)
            'id_skema',                 // 5. PK di tabel ini (skema)
            'id_kelompok_pekerjaan'     // 6. PK di tabel perantara (kelompok_pekerjaan)
        );
    }
    

    
    /**
     * Relasi Many-to-Many dengan model Asesor.
     * (Skema punya BANYAK Asesor melalui tabel pivot 'Transaksi_asesor_skema')
     */
    public function asesors(): BelongsToMany
    {
        // belongsToMany(ModelTerkait, NamaTabelPivot, FKModelIniDiPivot, FKModelTerkaitDiPivot)
        return $this->belongsToMany(Asesor::class, 'Transaksi_asesor_skema', 'id_skema', 'id_asesor')
                    // Menggunakan model pivot khusus untuk mengakses kolom tambahan
                    ->using(AsesorSkemaTransaksi::class)
                    // Karena tabel pivot memiliki timestamps
                    ->withTimestamps(); 
    }
    
    /**
     * Relasi One-to-Many ke model pivot (AsesorSkemaTransaksi).
     * (Opsional, untuk mengakses record transaksi secara langsung)
     */
    public function transaksiAsesorSkema(): HasMany
    {
        return $this->hasMany(AsesorSkemaTransaksi::class, 'id_skema', 'id_skema');
    }
}