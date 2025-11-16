<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTuk extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'jenis_tuk';

    /**
     * Primary key yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis_tuk';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = ['jenis_tuk'];


    /**
     * Mendefinisikan relasi one-to-many ke model Jadwal.
     */
    public function jadwal()
    {
        // Satu JenisTuk bisa memiliki banyak Jadwal
        return $this->hasMany(Jadwal::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }
}
