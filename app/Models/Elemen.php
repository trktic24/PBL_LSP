<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemen extends Model
{
    use HasFactory;

    protected $table = 'master_elemen';
    protected $primaryKey = 'id_elemen';

    protected $fillable = [
        'id_unit_kompetensi',
        'elemen',
    ];

    public function unitKompetensi()
    {
        return $this->belongsTo(UnitKompetensi::class, 'id_unit_kompetensi', 'id_unit_kompetensi');
    }

    public function kriteria()
    {
        return $this->hasMany(KriteriaUnjukKerja::class, 'id_elemen', 'id_elemen');
    }
    
}
