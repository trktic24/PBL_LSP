<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Beri tahu Laravel bahwa Primary Key Anda BUKAN 'id',
     * tapi 'id_user'.
     */
    protected $primaryKey = 'id_user';

    /**
     * Kolom yang boleh diisi secara massal.
     * Disesuaikan dengan ERD Anda.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'google_id',
    ];

    /**
     * Kolom yang harus disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi one-to-one (inverse): Satu User dimiliki oleh satu Role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id_role');
    }
}

