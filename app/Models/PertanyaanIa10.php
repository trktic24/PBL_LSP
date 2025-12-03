<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanIa10 extends Model
{
    use HasFactory;

    // Tentukan nama tabel & primary key
    protected $table = 'pertanyaan_ia10';
    protected $primaryKey = 'id_pertanyaan_ia10';
    protected $guarded = [];

    // Izinkan kolom-kolom ini diisi saat 'create'
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'id_ia10',
        'pertanyaan',
        'jawaban_pilihan_iya_tidak',
    ];

    public function dataSertifikasiAsesi()
    {
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi');
    }

    public function ia10()
    {
        return $this->belongsTo(Ia10::class, 'id_ia10');
    }
}