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
        'kode_unit',
        'judul_unit',
    ];

    /**
     * Relasi "hasMany":
     * Satu Unit Kompetensi dapat dimiliki oleh BANYAK Kelompok Pekerjaan.
     */
    public function kelompokPekerjaan()
    {
        return $this->belongsTo(
            KelompokPekerjaan::class,
            'id_unit_kompetensi', // Foreign key di tabel 'kelompok_pekerjaan'
            'id_unit_kompetensi'  // Primary key di tabel ini
        );
    }
}