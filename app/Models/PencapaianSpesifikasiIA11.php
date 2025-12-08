<?php

namespace App\Models\IA11;

use App\Models\IA11\SpesifikasiIA11;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencapaianSpesifikasiIA11 extends Model
{
    use HasFactory;

    protected $table = 'pencapaian_spesifikasi_ia11';
    protected $primaryKey = 'id_pencapaian_spesifikasi_ia11';
    public $timestamps = true;

    protected $fillable = ['id_ia11', 'id_spesifikasi_ia11', 'hasil_reviu', 'catatan_temuan'];

    public function spesifikasiItem(): BelongsTo
    {
        return $this->belongsTo(SpesifikasiIA11::class, 'id_spesifikasi_ia11', 'id_spesifikasi_ia11');
    }
}