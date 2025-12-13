<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponBuktiAk01 extends Model
{
    use HasFactory;

    protected $table = 'respon_bukti_ak01';
    protected $primaryKey = 'id_respon_bukti_ak01'; // Penting
    protected $guarded = [];

    // Izinkan kolom-kolom ini diisi saat 'create'
    protected $fillable = [
        'id_bukti_ak01',
        'id_data_sertifikasi_asesi',
        'respon',
    ];    

    // Relasi ke Bukti (opsional tapi bagus ada)
    public function bukti(): BelongsTo
    {
        return $this->belongsTo(BuktiAk01::class, 'id_bukti_ak01');
    }

    public function dataSertifikasiAsesi(): BelongsTo
    {
        // Kita perlu menentukan foreign key dan owner key secara eksplisit
        // karena keduanya tidak mengikuti konvensi standar Laravel.
        return $this->belongsTo(DataSertifikasiAsesi::class, 'id_data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
    }
}
