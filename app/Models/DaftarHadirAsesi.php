<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarHadirAsesi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'daftar_hadir_asesi';

    /**
     * Kunci primer yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_daftar_hadir_asesi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'hadir',
        'tanda_tangan_asesi',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hadir' => 'boolean',
    ];    

    /**
     * Mendapatkan data sertifikasi asesi yang memiliki daftar hadir ini.
     * Relasi: BelongsTo (Satu DaftarHadirAsesi dimiliki oleh satu DataSertifikasiAsesi)
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        // Foreign Key: id_data_sertifikasi_asesi (di tabel daftar_hadir_asesi)
        // Owner Key: id_data_sertifikasi_asesi (di tabel data_sertifikasi_asesi)
        return $this->belongsTo(
            DataSertifikasiAsesi::class,
            'id_data_sertifikasi_asesi',
            'id_data_sertifikasi_asesi'
        );
    }
}
