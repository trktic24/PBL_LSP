<?php

namespace App\Models; // <-- PASTIKAN INI ADALAH 'App\Models'

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Skema;                // Untuk relasi skema
use App\Models\User;                 // Untuk relasi user (jika Anda punya model User di App\Models)
use App\Models\DataPekerjaanAsesi;   // Untuk relasi dataPekerjaan
use App\Models\DataSertifikasiAsesi; // Untuk relasi dataSertifikasi

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
     * Relasi ke Skema
     * Ini adalah fungsi yang Anda tambahkan untuk memperbaiki error sebelumnya.
     */
    public function skema(): BelongsTo
    {
        // Asumsi: Tabel 'asesis' punya kolom 'skema_id' sebagai foreign key
        // Jika nama model skema Anda adalah MasterSkema, ganti Skema::class
        return $this->belongsTo(Skema::class, 'skema_id');
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        // Jika model User ada di namespace App\Models
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke DataPekerjaan
     */
    public function dataPekerjaan(): HasOne
    {
        return $this->hasOne(DataPekerjaanAsesi::class, 'id_asesi', 'id_asesi');
    }

    /**
     * Relasi ke DataSertifikasiAsesi
     */
    public function dataSertifikasi(): HasOne
    {
        return $this->hasOne(DataSertifikasiAsesi::class, 'id_asesi', 'id_asesi');
    }

} // <-- Kurung kurawal penutup class