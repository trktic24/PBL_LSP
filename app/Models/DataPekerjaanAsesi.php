<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPekerjaanAsesi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'data_pekerjaan_asesi';

    /**
     * Kunci primer yang terkait dengan tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_pekerjaan';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_asesi',
        'nama_institusi_pekerjaan',
        'alamat_institusi',
        'jabatan',
        'kode_pos_institusi',
        'no_telepon_institusi',
    ];

    /**
     * Mendapatkan data asesi yang memiliki pekerjaan ini.
     */
    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'id_asesi', 'id_asesi');
    }
}
