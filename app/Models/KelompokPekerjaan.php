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
        'id_skema',
        'nama_kelompok_pekerjaan',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    // Relasi ke Anak (Unit Kompetensi)
    public function unitKompetensis()
    {
        return $this->hasMany(UnitKompetensi::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }
}
