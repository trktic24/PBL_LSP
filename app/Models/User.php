<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Asesor; // <-- 1. TAMBAHKAN 'use' UNTUK ASESOR

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Hapus HasApiTokens jika tidak pakai Sanctum

    /**
     * Tentukan Primary Key-nya.
     */
    protected $primaryKey = 'id_user';

    /**
     * Kolom yang boleh diisi secara massal.
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
        return $this->belongsTo(Role::class);
    }

    /**
     * 2. TAMBAHKAN FUNGSI RELASI INI
     * Mendapatkan data Asesor yang terkait dengan User ini.
     */
    public function asesor()
    {
        // Asumsi: tabel 'asesor' punya foreign key 'id_user'
        // dan primary key di tabel 'users' juga 'id_user'
        return $this->hasOne(Asesor::class, 'id_user'); 
    }
}