<?php

namespace App\Models; // <-- PASTIKAN INI ADALAH 'App\Models'

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Skema;             
use App\Models\DataPekerjaanAsesi;   
use App\Models\DataSertifikasiAsesi; 
use App\Models\User;                 

class Asesi extends Model
{
    use HasFactory;

    // --- SEMUA PROPERTI DAN FUNGSI ADA DI DALAM SINI ---

    protected $table = 'asesi';
    protected $primaryKey = 'id_asesi';

    /**
     * Izinkan semua kolom diisi lewat factory/create().
     */
    protected $guarded = [];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        // Jika model User ada di namespace App\Models
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function dataPekerjaan(): HasOne
    {
        // Model Asesi HANYA memiliki SATU DataPekerjaanAsesi
        return $this->hasOne(DataPekerjaanAsesi::class, 'id_asesi', 'id_asesi');
    }
    
    /**
     * Relasi: Asesi bisa punya banyak data sertifikasi (history).
     */
    public function dataSertifikasi()
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_asesi', 'id_asesi');
    }

    public function buktiDasar()
    {
        // Relasi: Asesi -> DataSertifikasiAsesi -> BuktiDasar
        return $this->hasManyThrough(
            BuktiDasar::class,           // Model Tujuan
            DataSertifikasiAsesi::class, // Model Perantara
            'id_asesi',                  // Foreign Key di tabel Perantara (data_sertifikasi_asesi)
            'id_data_sertifikasi_asesi', // Foreign Key di tabel Tujuan (bukti_dasar)
            'id_asesi',                  // Local Key di tabel Asal (asesi)
            'id_data_sertifikasi_asesi'  // Local Key di tabel Perantara (data_sertifikasi_asesi)
        );
    }
}

