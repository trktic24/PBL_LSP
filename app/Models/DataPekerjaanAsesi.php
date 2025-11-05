<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPekerjaanAsesi extends Model
{
    // 1. (Opsional) Boleh ditambahin kalo lu mau bikin factory-nya juga
    use HasFactory;

    /**
     * 2. Kasih tau Laravel nama tabel-nya
     * (karena nama tabel lu 'data_pekerjaan_asesi')
     */
    protected $table = 'data_pekerjaan_asesi';

    /**
     * 3. Kasih tau Laravel nama Primary Key-nya
     * (karena lu pake 'id_pekerjaan_asesi', bukan 'id')
     */
    protected $primaryKey = 'id_pekerjaan_asesi';

    /**
     * 4. Izinkan mass assignment
     * (Biar gampang diisi pake factory atau ::create)
     */
    protected $guarded = [];


    // --- RELASI PENTING ---

    /**
     * Relasi one-to-one (inverse):
     * Satu data Pekerjaan ini DIMILIKI oleh satu Asesi.
     */
    public function asesi(): BelongsTo
    {
        // Model tujuan: Asesi::class
        // Foreign Key di tabel ini: 'id_asesi'
        // Primary Key di tabel asesi: 'id_asesi' (owner key)
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}