<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmpanBalikIa06 extends Model
{
    use HasFactory;

    protected $table = 'umpan_balik_ia06';
    protected $primaryKey = 'id_umpan_balik_ia06';

    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'umpan_balik',
    ];

    /**
     * Relasi: Umpan balik ini ditujukan untuk asesi siapa?
     */
    public function asesi(): BelongsTo
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}