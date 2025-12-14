<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyusunValidator extends Model
{
    use HasFactory;

    protected $table = 'penyusun_validator';
    protected $primaryKey = 'id_penyusun_validator';

    protected $fillable = [
        'id_penyusun',
        'id_validator',
        'id_data_sertifikasi_asesi',
        'tanggal_validasi',
    ];

    protected $casts = [
        'tanggal_validasi' => 'date',
    ];

    public function penyusun(): BelongsTo
    {
        return $this->belongsTo(Penyusun::class, 'id_penyusun', 'id_penyusun');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(Validator::class, 'id_validator', 'id_validator');
    }

    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}
