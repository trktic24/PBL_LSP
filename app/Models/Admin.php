<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'admin';

    // Primary key
    protected $primaryKey = 'id_admin';

    // Kolom yang boleh diisi
    protected $fillable = [
        'id_user',
        'nama_admin',
        'tanda_tangan_admin',
    ];

    // Relasi: Data Admin ini dimiliki oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user'); // FK, Owner Key
    }
}
