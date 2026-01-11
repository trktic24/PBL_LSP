<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpesifikasiTeknisIA11 extends Model
{
    use HasFactory;

    protected $table = 'spesifikasi_teknis_ia11';
    protected $primaryKey = 'id_spesifikasi_teknis_ia11';
    
    protected $guarded = []; 

    /**
     * Atribut yang dapat diisi (Fillable).
     */
    protected $fillable = [
        'id_ia11',
        'data_teknis',
    ];

    /**
     * Relasi ke IA11 (Parent)
     * Relasi: Many-to-One
     * Setiap baris data teknis dimiliki oleh satu produk IA11.
     */
    public function ia11(): BelongsTo
    {
        return $this->belongsTo(IA11::class, 'id_ia11', 'id_ia11');
    }
}