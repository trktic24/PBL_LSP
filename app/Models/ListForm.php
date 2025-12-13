<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListForm extends Model
{
    use HasFactory;

    protected $table = 'list_form'; // Sesuai nama tabel Anda
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_skema',
        // FASE 1
        'apl_01', 'apl_02',
        // FASE 2
        'fr_ia_01', 'fr_ia_02', 'fr_ia_03', 'fr_ia_04', 'fr_ia_05',
        'fr_ia_06', 'fr_ia_07', 'fr_ia_08', 'fr_ia_09', 'fr_ia_10', 'fr_ia_11',
        // FASE 3
        'fr_ak_01', 'fr_ak_02', 'fr_ak_03', 'fr_ak_04', 'fr_ak_05', 'fr_ak_06',
        // FASE 4
        'fr_mapa_01', 'fr_mapa_02'
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }
}