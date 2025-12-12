<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTUK extends Model
{
    use HasFactory;

    protected $table = 'jenis_tuk';
    protected $primaryKey = 'id_jenis_tuk'; // PENTING: Sesuaikan PK

    protected $guarded = [];

    // Relasi ke Jadwal (Satu Jenis TUK bisa dipakai di banyak Jadwal)
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }
}
