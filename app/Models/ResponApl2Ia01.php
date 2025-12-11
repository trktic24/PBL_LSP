<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponApl2Ia01 extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'respon_apl02_ia01';

    /**
     * Primary key yang digunakan oleh tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_respon_apl02';

    /**
     * Menunjukkan apakah model harus memiliki timestamp (created_at & updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_kriteria',
        'respon_asesi_apl02',
        'bukti_asesi_apl02',
        'pencapaian_ia01',
        'penilaian_lanjut_ia01',
    ];

    // =====================================================================
    // RELASI ELOQUENT
    // =====================================================================

    /**
     * Mendapatkan data sertifikasi asesi yang terkait dengan respon ini.
     */
    //public function dataSertifikasiAsesi(): BelongsTo
    //{
        // Asumsi: Model terkait bernama 'DataSertifikasiAsesi'
        // Asumsi: Foreign key 'id_data_sertifikasi_asesi'
      //  return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    //}

    /**
     * Mendapatkan kriteria yang terkait dengan respon ini.
     */
    public function kriteria(): BelongsTo
    {
        // Asumsi: Model terkait bernama 'Kriteria'
        // Asumsi: Foreign key 'id_kriteria'
        return $this->belongsTo(KriteriaUnjukKerja::class, 'id_kriteria');
    }
}
