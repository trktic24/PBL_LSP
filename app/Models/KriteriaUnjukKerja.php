<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KriteriaUnjukKerja extends Model
{
    use HasFactory;

    //kasih nama tabel yang sesuai dengan migrate
    protected $table = 'master_kriteria_unjuk_kerja';

    //namakan PK
    protected $primaryKey = 'id_kriteria';

    //buat diisi sama factory
    protected $guarded = [];

    /**
     * Relasi: 1 Kriteria DIMILIKI 1 Elemen.
     */
    public function elemen(): BelongsTo
    {
        return $this->belongsTo(Elemen::class, 'id_elemen', 'id_elemen');
    }

    /**
     * Relasi: 1 Kriteria PUNYA BANYAK ResponApl02Ia01
     */
    // public function responApl02Ia01s(): HasMany
    // {
    //     // Pastiin lu punya Model 'ResponApl02Ia01'
    //     return $this->hasMany(ResponApl02Ia01::class, 'id_kriteria', 'id_kriteria');
    // }
}

