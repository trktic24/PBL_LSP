<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Asumsi Model BuktiDasar ada di App\Models\BuktiDasar

class BuktiPortofolioIA08IA09 extends Model
{
    use HasFactory;

    protected $table = 'bukti_portofolio_ia08_ia09';
    protected $primaryKey = 'id_bukti_portofolio';

    protected $fillable = [
        'id_portofolio', 
        'id_ia08', 
         'daftar_pertanyaan_wawancara',
        'kesimpulan_jawaban_asesi', 
        'pencapaian_ia09',
        'is_valid', 
        'is_asli', 
        'is_terkini', 
        'is_memadai',
    ];


    /**
     * Relasi ke BuktiDasar, diasumsikan id_portofolio adalah foreign key ke BuktiDasar
     */
    public function buktiPortofolio()
    {
        return $this->belongsTo(DataPortofolio::class, 'id_portofolio', 'id_portofolio');
    }

    public function ia08()
    {
        return $this->belongsTo(Ia08::class, 'id_ia08', 'id_ia08');
    }
}