<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPekerjaanAsesi extends Model
{
    use HasFactory;

    protected $table = 'data_pekerjaan_asesi';
    protected $primaryKey = 'id_pekerjaan';

    protected $fillable = [
        'id_asesi',
        'nama_institusi_pekerjaan',
        'alamat_institusi',
        'jabatan',
        'kode_pos_institusi',
        'no_telepon_institusi',
    ];

    // Relasi balik ke Asesi
    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}