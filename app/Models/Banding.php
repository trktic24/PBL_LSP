<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banding extends Model
{
    use HasFactory;

    protected $table = 'banding'; // pastikan nama tabel sesuai

    protected $primaryKey = 'id_banding';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'tuk_sewaktu',
        'tuk_tempatkerja',
        'tuk_mandiri',
        'ya_tidak_1',
        'ya_tidak_2',
        'ya_tidak_3',
        'alasan_banding',
        'tanggal_pengajuan_banding',
        'tanda_tangan_asesi',
    ];

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}