<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiAk01 extends Model
{
    use HasFactory;

    protected $table = 'bukti_ak01';
    protected $primaryKey = 'id_bukti_ak01';

    protected $fillable = [
        'bukti',
    ];
    protected $guarded = [];

    /**
     * Relasi One-to-Many: Satu Bukti Master bisa dipilih oleh BANYAK respon.
     */
    public function respon()
    {
        // hasMany(ModelTujuan, Foreign_Key_Di_ModelTujuan, Local_Key_Di_Sini)
        return $this->hasMany(ResponBuktiAk01::class, 'id_bukti_ak01', 'id_bukti_ak01');
    }
}
