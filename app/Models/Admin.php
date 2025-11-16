<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'data_admin'; // Pastikan nama tabel ini sesuai

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id']; // Melindungi 'id' saja

    /**
     * Mendapatkan data User yang memiliki profil Admin ini.
     */
    public function user()
    {
        // Asumsi foreign key di tabel 'data_admin' adalah 'user_id'
        // Asumsi primary key di tabel 'users' adalah 'id_user'
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}