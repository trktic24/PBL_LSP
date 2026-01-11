<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpesifikasiProdukIA11 extends Model
{
    use HasFactory;

    protected $table = 'spesifikasi_produk_ia11';
    protected $primaryKey = 'id_spesifikasi_produk_ia11'; 
    protected $guarded = []; 

    /**
     * Atribut yang dapat diisi (Fillable).
     */
    protected $fillable = [
        'id_ia11',
        'dimensi_produk',
        'berat_produk',
    ];

    public function ia11(): BelongsTo
    {
        return $this->belongsTo(IA11::class, 'id_ia11', 'id_ia11');
    }
}