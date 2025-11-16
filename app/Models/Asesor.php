<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;
    protected $table = 'asesor';
    protected $primaryKey = 'id_asesor';
    protected $guarded = ['id_asesor'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // HAPUS atau GANTI FUNGSI INI:
    // public function skema()
    // {
    //     return $this->belongsTo(Skema::class, 'skema_id');
    // }

    // GANTI DENGAN FUNGSI INI:
    /**
     * Skema yang dimiliki/diampu oleh Asesor ini.
     */
    public function skema()
    {
        return $this->belongsToMany(
            Skema::class,           // Model tujuan
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_asesor',            // Foreign key di pivot untuk model ini
            'id_skema'              // Foreign key di pivot untuk model tujuan
        );
    }
}