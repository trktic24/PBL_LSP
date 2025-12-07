<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPortofolioIa08Ia09 extends Model
{
    use HasFactory;

    protected $table = 'bukti_portofolio_ia08_ia09';
    protected $primaryKey = 'id_bukti_portofolio';

    protected $fillable = [
        'id_portofolio',
        'id_ia08',
        'is_valid',
        'is_asli',
        'is_terkini',
        'is_memadai',
        'kesimpulan_jawaban_asesi',
        'pencapaian_ia09',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'is_asli' => 'boolean',
        'is_terkini' => 'boolean',
        'is_memadai' => 'boolean',
    ];

    /**
     * Relasi ke portofolio (jika ada tabel portofolio)
     */
    public function portofolio()
    {
        return $this->belongsTo(Portofolio::class, 'id_portofolio', 'id_portofolio');
    }

    /**
     * Relasi ke IA08 (jika ada tabel ia08)
     * Sesuaikan dengan nama model dan tabel yang sebenarnya
     */
    public function ia08()
    {
        return $this->belongsTo(Ia08::class, 'id_ia08', 'id_ia08');
    }

}