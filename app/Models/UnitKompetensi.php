<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KelompokPekerjaan;

class UnitKompetensi extends Model
{
    use HasFactory;

    protected $table = 'unit_kompetensi';
    protected $primaryKey = 'id_unit_kompetensi';

    protected $fillable = [
        'id_kelompok_pekerjaan',
        'urutan',
        'kode_unit',
        'judul_unit',
    ];

    // Relasi ke atas (Kelompok Pekerjaan)
    public function kelompokPekerjaan()
    {
        return $this->belongsTo(KelompokPekerjaan::class, 'id_kelompok_pekerjaan', 'id_kelompok_pekerjaan');
    }
}