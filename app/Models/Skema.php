<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'nomor_skema',
        'nama_skema',
        'deskripsi_skema',
        'harga',
        'SKKNI',
        'gambar',
    ];

    /**
     * Relasi ke Categorie (Berdasarkan foreignId 'category_id').
     * Menandakan bahwa Skema ini 'milik' satu Categorie.
     */
    public function categorie()
    {
        // Model, foreign_key, owner_key
        return $this->belongsTo(Categorie::class, 'category_id', 'id');
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
}
