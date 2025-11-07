<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * TAMBAHKAN INI:
     * Beri tahu Eloquent bahwa primary key Anda bukan 'id'.
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name', // Ganti 'name' menjadi 'username' sesuai migrasi
        'username', // <-- Sesuai migrasi users_table.php
        'email',
        'password',
        'role_id', // <-- Tambahkan ini
        'google_id', // <-- Tambahkan ini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        // Relasi ini perlu foreign key 'role_id' dan primary key 'id_role'
        // Ini sudah benar sesuai migrasi users_table Anda
        return $this->belongsTo(Role::class, 'role_id', 'id_role');
    }

    public function asesor()
    {
        // Relasi ini sudah benar, akan mencari 'user_id' di tabel data_asesor
        return $this->hasOne(Asesor::class, 'user_id', 'id_user');
    }

    public function asesi()
    {
        // Relasi ini sudah benar, akan mencari 'user_id' di tabel data_asesi
        return $this->hasOne(Asesi::class, 'user_id', 'id_user');
    }

    // Anda juga punya data_admin, tambahkan ini jika perlu
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'id_user');
    }
}