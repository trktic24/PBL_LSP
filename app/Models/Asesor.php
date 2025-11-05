<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Pastikan ini ada
use App\Models\Skema; // Tambahkan ini

class Asesor extends Model
{
    // Nama tabel secara eksplisit (Sudah benar)
    protected $table = 'asesor'; 

    // Kolom-kolom yang boleh diisi secara massal (PENTING untuk keamanan)
    // Sesuaikan kolom ini dengan kolom lain yang ada di tabel 'asesor'
    protected $fillable = [
        'id_user',
        'id_skema',
        // tambahkan kolom lain seperti 'nama', 'nip', dll.
    ];

    // Relasi: Asesor milik satu User (Sudah benar)
    public function user()
    {
        // Secara default akan mencari 'user_id' di tabel 'asesor', 
        // tapi Anda sudah secara eksplisit menunjuk 'id_user' (Sudah benar)
        return $this->belongsTo(User::class, 'id_user');
    } 

    // Relasi: Asesor milik satu Skema (Perlu Ditambahkan)
    public function skema()
    {
        // Anggap foreign key di tabel 'asesor' adalah 'id_skema' 
        // dan primary key di tabel 'skema' adalah 'id'
        return $this->belongsTo(Skema::class, 'id_skema');
    } 
}