<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKompetensi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'unit_kompetensi';

    /**
     * Primary key untuk model ini, sesuai migration.
     *
     * @var string
     */
    protected $primaryKey = 'id_unit_kompetensi';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Sesuai dengan kolom di migration.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kelompok_pekerjaan',
        'urutan',
        'kode_unit',
        'judul_unit',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    public function kelompokPekerjaan()
    {
        return $this->belongsTo(KelompokPekerjaan::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }

    // Relasi ke Anak Langsung (Elemen)
    public function elemens()
    {
        return $this->hasMany(Elemen::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }

    // MAGIC RELATION: Ambil KUK langsung dari Unit (melewati Elemen)
    // Ini yang dipake di Controller: $unit->kriteriaUnjukKerja
    public function kriteriaUnjukKerja()
    {
        return $this->hasManyThrough(
            KriteriaUnjukKerja::class, // Target (KUK)
            Elemen::class,             // Perantara (Elemen)
            'id_unit_kompetensi',      // FK di tabel Elemen
            'id_elemen',               // FK di tabel KUK
            'id_unit_kompetensi',      // PK di tabel Unit
            'id_elemen'                // PK di tabel Elemen
        );
    }
}
