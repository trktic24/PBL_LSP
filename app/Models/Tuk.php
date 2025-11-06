<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mewakili tabel TUK (Tempat Uji Kompetensi) Master.
 */
class Tuk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * (Asumsi: 'tuk' berdasarkan FK di tabel jadwal)
     */
    protected $table = 'tuk';

    /**
     * Primary key untuk model ini.
     */
    protected $primaryKey = 'id_tuk';

    /**
     * Menonaktifkan timestamps (created_at, updated_at).
     */
    public $timestamps = false;

    /**
     * Kolom yang diizinkan untuk diisi (mass-assignable).
     * (Asumsi: ada 1 kolom 'foto' untuk menyimpan path gambar)
     */
    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'kontak',
        'foto',
    ];

    // =====================================================================
    // RELASI ELOQUENT
    // =====================================================================

    /**
     * Mendefinisikan relasi "one-to-many" ke model Jadwal.
     * Satu TUK bisa memiliki banyak Jadwal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jadwal(): HasMany
    {
        // Asumsi: Nama model untuk tabel 'jadwal (Master)' adalah 'Jadwal'
        // dan foreign key di sana adalah 'id_tuk'.
        return $this->hasMany(Jadwal::class, 'id_tuk', 'id_tuk');
    }
}
