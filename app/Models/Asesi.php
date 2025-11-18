<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'asesi';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_asesi';

    /**
     * Izinkan semua kolom diisi.
     */
    protected $guarded = [];

    /**
     * Tentukan kolom yang harus diperlakukan sebagai tanggal.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Definisikan relasi: Satu Asesi 'dimiliki oleh' satu User.
     */
    public function user()
    {
        // Terhubung ke model User, menggunakan 'id_user' sebagai foreign key
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
}