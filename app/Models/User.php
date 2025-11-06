<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Hapus HasApiTokens jika tidak pakai Sanctum

    /**
     * Kolom yang boleh diisi secara massal.
     * Disesuaikan dengan ERD Anda.
     */
    protected $table ='users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id', // Tambahkan role_id
        'email',
        'password',
        'google_id', // Tambahkan google_id
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

    // Relasi: Satu User punya satu Role
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id'); // 'role_id' adalah foreign key di tabel users
    }

    // Relasi: Satu User punya satu data Asesi (jika dia asesi)
    public function asesi(): HasOne
    {
        return $this->hasOne(Asesi::class, 'id_user', 'id_user'); // FK, Owner Key
    }

    // Relasi: Satu User punya satu data Asesor (jika dia asesor)
    public function asesor(): HasOne
    {
        return $this->hasOne(Asesor::class, 'id_user', 'id_user'); // FK, Owner Key
    }

    // Relasi: Satu User punya satu data Admin (jika dia admin)
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'id_user', 'id_user'); // FK, Owner Key
    }

    // Helper cek role (opsional tapi berguna)
    public function hasRole(string $roleName): bool
    {
        return $this->role()->where('nama_role', $roleName)->exists();
    }
    /**
     * Bikin atribut 'nama_lengkap' palsu yang ngambil data
     * dari relasi asesi atau asesor.
     *
     * @return string
     */
    public function getNamaLengkapAttribute(): string
    {
        // Cek dulu rolenya, 'role' bakal di-lazy-load di sini
        if ($this->role->nama_role === 'asesi') {
            // Kalo rolenya asesi, ambil nama dari relasi asesi
            // 'asesi' bakal di-lazy-load di sini
            return $this->asesi->nama_lengkap ?? 'Asesi';

        } elseif ($this->role->nama_role === 'asesor') {
            // Kalo rolenya asesor, ambil nama dari relasi asesor
            // 'asesor' bakal di-lazy-load di sini
            return $this->asesor->nama_lengkap ?? 'Asesor';

        } elseif ($this->role->nama_role === 'admin') {
            // Admin mungkin gak punya tabel profil, jadi kasih default
            return 'Administrator';
        }

        // Fallback terakhir
        return 'User';
    }
}

