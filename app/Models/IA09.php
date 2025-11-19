<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IA09 extends Model
{
    use HasFactory; 

    protected $table = 'ia09_forms'; 
    protected $guarded = ['id']; 
    
    // Casting kolom JSON dan Tanggal
    protected $casts = [
        'questions' => 'array', 
        'units' => 'array', 
        'tanggal_asesmen' => 'date', 
    ];

    /**
     * Relasi BelongsTo ke Model Asesi (SUDAH DIPERBAIKI).
     * Menggunakan format 3 parameter: (Model Tujuan, Foreign Key di tabel INI, Primary Key di tabel TARGET)
     * Asumsi Foreign Key di ia09_forms: 'asesi_id'
     * Asumsi Primary Key di Asesi: 'id_asesi'
     */
    public function asesi(): BelongsTo
    {
        // Parameter 1: Asesi::class (Model tujuan)
        // Parameter 2: 'asesi_id' (Foreign Key di tabel ia09_forms)
        // Parameter 3: 'id_asesi' (Primary Key di tabel Asesi)
        return $this->belongsTo(Asesi::class, 'asesi_id', 'id_asesi'); 
    }

    /**
     * Relasi BelongsTo ke Model Asesor (SUDAH DIPERBAIKI).
     * Menggunakan format 3 parameter: (Model Tujuan, Foreign Key di tabel INI, Primary Key di tabel TARGET)
     * Asumsi Foreign Key di ia09_forms: 'asesor_id'
     * Asumsi Primary Key di Asesor: 'id_asesor'
     */
    public function asesor(): BelongsTo
    {
        // Parameter 1: Asesor::class (Model tujuan)
        // Parameter 2: 'asesor_id' (Foreign Key di tabel ia09_forms)
        // Parameter 3: 'id_asesor' (Primary Key di tabel Asesor)
        return $this->belongsTo(Asesor::class, 'asesor_id', 'id_asesor');
    }
    
    /**
     * Relasi ke Model Skema.
     */
    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }
}