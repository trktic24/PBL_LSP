<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KriteriaUntukKerja extends Model
{
    use HasFactory;

    protected $table = 'kriteria_untuk_kerja';
    protected $primaryKey = 'id_kriteria';

    protected $guarded = [];

    /**
     * Relasi ke Elemen (Many to One)
     * Kriteria belongs to Elemen
     */
    public function elemen(): BelongsTo
    {
        return $this->belongsTo(Elemen::class, 'id_elemen', 'id_elemen');
    }
}
