<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional, tapi disarankan)
    protected $table = 'mitras';

    // Daftar kolom yang diizinkan untuk diisi data (Mass Assignment)
    // Kolom ini HARUS sama dengan input yang kamu kirim di Postman
    protected $fillable = [
        'nama_mitra',
        'alamat',
        'no_telp',
        'email',
        'logo',
    ];
}