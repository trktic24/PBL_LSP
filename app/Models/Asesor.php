<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';
    protected $primaryKey = 'id_asesor';

    /**
     * $guarded adalah cara yang benar
     * agar Seeder bisa mengisi 'id_user' dan 'id_skema' (dari factory).
     */
    protected $guarded = ['id_asesor'];

    /**
     * DIPERBAIKI: Relasi ke User
     * Foreign key di tabel 'asesor' adalah 'id_user'.
     * Primary key di tabel 'users' adalah 'id_user'.
     */
    public function user()
    {
        // INI ADALAH PERBAIKANNYA
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Skema (via tabel pivot)
     * Ini sudah benar sesuai kode Anda sebelumnya.
     */
    public function skema()
    {
        return $this->belongsToMany(
            Skema::class,
            'transaksi_asesor_skema', // Nama tabel pivot
            'id_asesor',                // Foreign key di pivot untuk model ini
            'id_skema'                  // Foreign key di pivot untuk model tujuan
        );
    }
}