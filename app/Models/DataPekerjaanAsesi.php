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
        'nama_institusi_perusahaan',
        'jabatan',
        'alamat_kantor',
        'kode_pos',
        'no_telepon_kantor',
    ];

    public function asesi(): BelongsTo
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}
