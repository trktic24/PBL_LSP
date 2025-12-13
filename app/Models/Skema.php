<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Category;
use App\Models\Asesor;
use App\Models\Jadwal;

class Skema extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'skema';
    /**
     * Primary key untuk model ini.
     * Sesuai migration: $table->id('id_skema');
     *
     * @var string
     */
    protected $primaryKey = 'id_skema';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Disesuaikan dengan kolom di migration.
     *
     * @var array<int, string>
     */
    protected $fillable = ['categorie_id', 'nomor_skema', 'nama_skema', 'deskripsi_skema', 'harga', 'SKKNI', 'gambar'];

    /**
     * Relasi ke Categorie (Berdasarkan foreignId 'categorie_id').
     * Menandakan bahwa Skema ini 'milik' satu Categorie.
     */
    public function category()
    {
        // Model, foreign_key, owner_key
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function kelompokPekerjaan()
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
     * Relasi ke Asesor (Many-to-Many).
     * Skema ini 'memiliki dan dimiliki oleh banyak' Asesor.
     */
    public function asesor()
    {
        return $this->belongsToMany(
            Asesor::class,
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_skema', // Foreign key di pivot untuk model ini (Skema)
            'id_asesor', // Foreign key di pivot untuk model tujuan (Asesor)
        );
    }

    /**
     * Relasi "Jalan Pintas" (HasManyThrough)
     * Skema -> (lewat KelompokPekerjaan) -> UnitKompetensi
     */
    public function unitKompetensi()
    {
        return $this->hasManyThrough(
            UnitKompetensi::class, // 1. Model Tujuan (Unit)
            KelompokPekerjaan::class, // 2. Model Perantara (Kelompok)
            'id_skema', // 3. FK di tabel perantara (kelompok_pekerjaan)
            'id_kelompok_pekerjaan', // 4. FK di tabel tujuan (unit_kompetensi)
            'id_skema', // 5. PK di tabel ini (skema)
            'id_kelompok_pekerjaan', // 6. PK di tabel perantara (kelompok_pekerjaan)
        );
    }
}
