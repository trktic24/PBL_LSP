<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  
use App\Models\Skema; 

class Asesor extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'asesor';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_asesor';

    /**
     * Kolom yang DIJAGA (selain ini boleh diisi).
     * Kosongkan array ini agar semua kolom bisa diisi.
     */
    protected $guarded = [];

    /**
     * Relasi ke model User.
     * Satu Asesor dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke model Skema.
     * Satu Asesor terkait dengan satu Skema.
     */
    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }
}