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

    /**
     * Tentukan kolom yang harus diperlakukan sebagai tanggal.
     */
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

    public function dataPekerjaan()
    {
        return $this->hasMany(DataPekerjaanAsesi::class, 'id_asesi', 'id_asesi');
    }
    
    /**
     * Relasi: Asesi bisa punya banyak data sertifikasi (history).
     */
    public function dataSertifikasi()
    {
        return $this->hasMany(DataSertifikasiAsesi::class, 'id_asesi', 'id_asesi');
    }
}

