<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiKelengkapan extends Model
{
    use HasFactory;
    protected $table = 'bukti_kelengkapan';
    protected $primaryKey = 'id_bukti_kelengkapan';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'jenis_dokumen',
        'keterangan', // Sekarang kita bisa simpan keterangan
        'bukti_kelengkapan',
        'status_kelengkapan',
    ];

    public function DataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}