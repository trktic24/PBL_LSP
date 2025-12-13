<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini jika Model Category menggunakan BelongsTo

// Pastikan semua Model relasi di-import di sini:
use App\Models\Category;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\KelompokPekerjaan;

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
        // Model, foreign_key, owner_key
        // Key kedua ('id_kelompok_pekerjaan') dispesifikkan karena 
        // nama kolom di tabel parent (kelompok_pekerjaan) tidak 'id'.
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

    public function jadwal(): HasMany // Gunakan HasMany untuk ketepatan tipe
    {
        return $this->hasMany(Jadwal::class, 'id_skema', 'id_skema');
        // --- Relasi yang sudah ada di kode Anda (tetap valid) ---
    }
    /**
     * Relasi ke UnitKompetensi.
     * Skema ini 'memiliki banyak' UnitKompetensi.
     */
    public function categorie()
    {
        return $this->hasManyThrough(
            UnitKompetensi::class,
            KelompokPekerjaan::class,
            'id_skema',              // FK di tabel kelompok_pekerjaan
            'id_kelompok_pekerjaan', // FK di tabel unit_kompetensi
            'id_skema',              // PK di tabel skema
            'id_kelompok_pekerjaan'  // PK di tabel kelompok_pekerjaan
        );
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
            'id_skema',               // Foreign key di pivot untuk model ini (Skema)
            'id_asesor'               // Foreign key di pivot untuk model tujuan (Asesor)
        );
    }

    /**
     * Relasi ke KelompokPekerjaan (Berdasarkan foreignId 'id_kelompok_pekerjaan').
     * Menandakan bahwa Skema ini 'milik' satu KelompokPekerjaan.
     */
    public function kelompokPekerjaans()
    {
        return $this->hasMany(KelompokPekerjaan::class, 'id_skema', 'id_skema');
    }

    // --- Relasi yang sudah ada di kode Anda (tetap valid) ---

    /**
     * Relasi ke UnitKompetensi.
     * Skema ini 'memiliki banyak' UnitKompetensi.
     */
    public function unitKompetensis()
    {
        // Asumsi foreign key di tabel 'unit_kompetensi' adalah 'skema_id'
        // dan primary key di 'skema' adalah 'id_skema' (sesuai $primaryKey di atas)
        return $this->hasMany(UnitKompetensi::class, 'skema_id', 'id_skema');
    }

}
