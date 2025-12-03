<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ia02 extends Model
{
    use HasFactory;
    protected $table = 'ia02';
    protected $primaryKey = 'id_ia02';
    protected $guarded = [];
    protected $casts = [
        // 'waktu' => 'datetime:H:i:s', // Bisa di-cast untuk format yang lebih spesifik jika diperlukan
    ];

    public function dataSertifikasiAsesi(): BelongsTo{
        return $this-> belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi','id_data_sertifikasi_asesi');
    }

    
}
