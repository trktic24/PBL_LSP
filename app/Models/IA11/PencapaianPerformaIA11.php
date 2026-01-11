<?php

namespace App\Models\IA11;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencapaianPerformaIA11 extends Model
{
    use HasFactory;

    protected $table = 'pencapaian_performa_ia11';
    protected $primaryKey = 'id_pencapaian_performa_ia11';
    public $timestamps = true;

    protected $fillable = [
        'id_ia11',
        'id_performa_ia11',
        'hasil_reviu',
        'catatan_temuan',
    ];

    protected $casts = [
        'hasil_reviu' => 'boolean'
    ];

    // ================= RELATIONSHIPS =================

    public function ia11(): BelongsTo
    {
        return $this->belongsTo(IA11::class, 'id_ia11');
    }

    public function performaItem(): BelongsTo
    {
        return $this->belongsTo(PerformaIA11::class, 'id_performa_ia11');
    }
}
