<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UnitKompetensi;
use App\Models\Skema;

class KelompokPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'kelompok_pekerjaan';
    protected $primaryKey = 'id_kelompok_pekerjaan';

    protected $fillable = [
        'id_skema',
        'nama_kelompok_pekerjaan',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }

    // Relasi ke Anak (Unit Kompetensi)
    public function unitKompetensis()
    {
        return $this->hasMany(UnitKompetensi::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }
}
