<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable; // Hapus HasApiTokens jika tidak pakai Sanctum

    /**
     * TAMBAHKAN INI:
     * Beri tahu Eloquent bahwa primary key Anda bukan 'id'.
     */
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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

    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
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
        return $this->hasOne(Asesor::class, 'id_user', 'id_user');
    }

    public function asesi()
    {
        // Relasi ini sudah benar, akan mencari 'user_id' di tabel data_asesi
        return $this->hasOne(Asesi::class, 'id_user', 'id_user');
    }

    // Anda juga punya data_admin, tambahkan ini jika perlu
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user', 'id_user');
    }

    /**
     * Check if user has specific role
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        if ($this->role && $this->role->nama_role === $roleName) {
            return true;
        }
        return false;
    }

    /**
     * Accessor untuk foto profil user dynamic berdasarkan role.
     */
    public function getPhotoUrlAttribute()
    {
        // 1. Jika Asesor
        if ($this->hasRole('asesor') && $this->asesor) {
            return $this->asesor->url_foto;
        }

        // 2. Jika Asesi (Belum ada kolom foto khusus, return default)
        if ($this->hasRole('asesi')) {
             return asset('images/profil_asesor.jpeg'); // Bisa diganti default asesi
        }

        // 3. Jika Admin (Belum ada kolom foto khusus)
        if ($this->hasRole('admin') || $this->hasRole('superadmin')) {
             return asset('images/profil_asesor.jpeg'); // Bisa diganti default admin
        }

        // Default global
        return asset('images/profil_asesor.jpeg');
    }
}
