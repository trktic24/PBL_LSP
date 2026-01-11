<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpesifikasiIA11 extends Model
{
    use HasFactory;

    protected $table = 'spesifikasi_ia11';
    protected $primaryKey = 'id_spesifikasi_ia11';
    public $timestamps = true;

    // MASTER DATA LSP – DIKUNCI TOTAL
    protected $fillable = [
        'deskripsi_spesifikasi',
    ];

    public function pencapaianSpesifikasi(): HasMany
    {
        return $this->hasMany(
            PencapaianSpesifikasiIA11::class,
            'id_spesifikasi_ia11',
            'id_spesifikasi_ia11'
        );
    }
}
