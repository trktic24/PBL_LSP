<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asesi extends Model
{
    use HasFactory;

    // Pastiin ini semua ada
    protected $table = 'asesi';
    protected $primaryKey = 'id_asesi';

    // --- INI SOLUSINYA ---
    /**
     * Izinkan semua kolom diisi lewat factory/create().
     */
    protected $guarded = [];
    // -----------------------

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke DataPekerjaan
     */
    public function dataPekerjaan(): HasOne
    {
        // Pastiin nama Model-nya bener (DataPekerjaanAsesi)
        return $this->hasOne(DataPekerjaanAsesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasi(): HasOne
    {
        return $this->hasOne(DataSertifikasiAsesi::class, 'id_asesi', 'id_asesi');
    }
}
