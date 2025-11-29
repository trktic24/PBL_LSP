<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResponAk04 extends Model
{
    use HasFactory;

    protected $table = 'respon_ak04';
    protected $primaryKey = 'id_respon_ak04';
    
    // Semua kolom diizinkan untuk diisi (gunakan guarded = [] atau fillable)
    protected $guarded = [];

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasiAsesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }

    /**
     * Casting boolean fields
     */
    protected $casts = [
        'penjelasan_banding' => 'boolean',
        'diskusi_dengan_asesor' => 'boolean',
        'melibatkan_orang_lain' => 'boolean',
    ];
}