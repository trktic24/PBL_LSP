<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPekerjaanAsesi extends Model
{
    use HasFactory;

    protected $table = 'data_pekerjaan_asesi';
    protected $primaryKey = 'id_pekerjaan';

    protected $fillable = [
    'id_asesi',
    'nama_institusi_pekerjaan',
    'jabatan',
    'alamat_institusi',
    'kode_pos_institusi',
    'no_telepon_institusi',
    ];

    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}
