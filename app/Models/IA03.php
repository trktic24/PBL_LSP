<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IA03 extends Model
{
    use HasFactory;

    protected $table = 'ia03';

    // primary key
    protected $primaryKey = 'id_IA03';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_kelompok_pekerjaan',
        'pertanyaan',
        'tanggapan',
        'pencapaian',
        'catatan_umpan_balik',
    ];

    protected $casts = [
        'pencapaian' => 'boolean',
    ];

    public function umpanBalik()
    {
        return $this->hasMany(UmpanBalikIA03::class, 'id_ia03', 'id_ia03');
    }


    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}
