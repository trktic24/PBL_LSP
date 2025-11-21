<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ia02 extends Model
{
    use HasFactory;

    /**
     * Menentukan nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'ia02';

    /**
     * Menentukan primary key untuk tabel ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_ia02';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_data_sertifikasi_asesi',
        'skenario',
        'peralatan',
        'waktu',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model DataSertifikasiAsesi.
     * * Ann-Note: Asumsi nama model adalah 'DataSertifikasiAsesi'
     * dan foreign key adalah 'id_data_sertifikasi_asesi'.
     */
    public function DataSertifikasiAsesi(): BelongsTo
    {
        // Sesuaikan 'App\Models\DataSertifikasiAsesi' jika nama model Anda berbeda
        // Sesuaikan 'id_data_sertifikasi_asesi' jika nama foreign key Anda berbeda
        return $this->belongsTo(
            DataSertifikasiAsesi::class, 
            'id_data_sertifikasi_asesi', // Foreign key di tabel skenario_ia02
            'id_data_sertifikasi_asesi'  // Primary key di tabel data_sertifikasi_asesi
        );
    }
}
