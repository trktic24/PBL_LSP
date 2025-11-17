<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPekerjaan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'kelompok_pekerjaan';

    /**
     * Primary key untuk model ini, sesuai migration.
     *
     * @var string
     */
    protected $primaryKey = 'id_kelompok_pekerjaan';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_unit_kompetensi',
        'nama_kelompok_pekerjaan',
    ];

    /**
     * Relasi "belongsTo":
     * Satu Kelompok Pekerjaan dimiliki oleh satu Unit Kompetensi.
     */
    public function unitKompetensi()
    {
        return $this->belongsTo(
            UnitKompetensi::class,
            'id_unit_kompetensi', // Foreign key di tabel ini
            'id_unit_kompetensi'  // Primary key di tabel 'unit_kompetensi'
        );
    }

    /**
     * Relasi "hasMany":
     * Satu Kelompok Pekerjaan bisa memiliki banyak Skema.
     * (Ini untuk melengkapi relasi dari sisi Skema)
     */
    public function skema()
    {
        return $this->hasMany(
            Skema::class,
            'id_kelompok_pekerjaan', // Foreign key di tabel 'skema'
            'id_kelompok_pekerjaan'  // Primary key di tabel ini
        );
    }
}