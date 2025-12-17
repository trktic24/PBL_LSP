<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrIa04a extends Model
{
    use HasFactory;

    // TAMBAHKAN INI: Memberi tahu Laravel nama tabel yang benar
    protected $table = 'fr_ia_04as'; 

    // Pastikan data bisa diisi (Mass Assignment)
    protected $guarded = ['id'];

    // Relasi ke User (Milik siapa form ini?)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // Relasi ke Asesor (Siapa yang tanda tangan?)
    public function asesor()
    {
        return $this->belongsTo(User::class, 'asesor_id', 'id_user');
    }
}