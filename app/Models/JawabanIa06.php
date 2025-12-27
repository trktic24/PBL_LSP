<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanIa06 extends Model
{
    use HasFactory;

    protected $table = 'jawaban_ia06';
    protected $primaryKey = 'id_jawaban_ia06';

    protected $fillable = [
        'id_soal_ia06',
        'id_data_sertifikasi_asesi',
        'jawaban_asesi',
        'pencapaian', // 1=Ya, 0=Tidak, Null=Belum Dinilai
    ];

    /**
     * Relasi: Jawaban ini milik soal mana?
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(SoalIA06::class, 'id_soal_ia06', 'id_soal_ia06');
    }

    /**
     * Relasi: Jawaban ini milik asesi siapa?
     * (Pastikan Anda sudah punya Model DataSertifikasiAsesi)
     */
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}