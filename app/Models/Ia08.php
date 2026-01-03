<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ia08 extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'ia08';

    /**
     * Primary key yang digunakan oleh tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_ia08';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'bukti_tambahan',
        'rekomendasi',
        'kelompok_pekerjaan',
        'unit_kompetensi',
        'elemen',
        'kuk',
    ];

    /**
     * Relasi ke model DataSertifikasiAsesi.
     * IA-08 dimiliki oleh satu data sertifikasi asesi.
     *
     * @return BelongsTo
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}