<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // --- PASTIIN INI SEMUA ADA ---
    protected $primaryKey = 'id_user';

    /**
     * Izinkan kolom ini diisi sama Factory
     */
    protected $fillable = ['username', 'email', 'password', 'role_id', 'google_id'];
    // ----------------------------

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke Role: 1 User punya 1 Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id_role');
    }

    /**
     * Relasi ke Asesi: 1 User punya 1 data Asesi
     */
    public function asesi()
    {
        // FK di 'asesi' adalah 'id_user', PK di 'users' adalah 'id_user'
        return $this->hasOne(asesi::class, 'id_user', 'id_user');
    }
}
