<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ak02 extends Model
{
    use HasFactory;

    protected $table = 'ak02';
    protected $primaryKey = 'id_ak02';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_unit_kompetensi',
        'jenis_bukti',        // Kolom Baru
        'kompeten',
        'tindak_lanjut',
        'komentar',
    ];

    // CASTING: Ubah JSON di database otomatis jadi Array di PHP
    protected $casts = [
        'jenis_bukti' => 'array',
    ];

    // Relasi ke Master Unit Kompetensi
    public function unitKompetensi()
    {
        return $this->belongsTo(UnitKompetensi::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }

    // Relasi ke Data Sertifikasi (Opsional, jika butuh balik)
    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}