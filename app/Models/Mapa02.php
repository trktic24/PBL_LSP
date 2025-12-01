<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mapa02 extends Model
{
    use HasFactory;

    /**
     * Menentukan nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'mapa02';

    /**
     * Menentukan primary key untuk tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_mapa02';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_kelompok_pekerjaan',
        'instrumen_asesmen',
        'potensi_asesi',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model DataSertifikasiAsesi.
     */
    public function DataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model KelompokPekerjaan.
     */
    public function KelompokPekerjaan(): BelongsTo
    {
        return $this->belongsTo(KelompokPekerjaan::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }
}
