<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    /**
     * (WAJIB) Memberi tahu Laravel nama tabel yang benar.
     */
    protected $table = 'roles';

    /**
     * (WAJIB) Memberi tahu Laravel primary key yang benar.
     */
    protected $primaryKey = 'id_role';

    /**
     * (WAJIB) Memberi tahu Laravel bahwa 'id_role' bukan auto-incrementing
     * jika Anda mengisinya secara manual di seeder (cth: 1, 2, 3).
     * Jika auto-increment, Anda bisa hapus baris ini.
     */
    public $incrementing = false; 
    
    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'nama_role',
    ];

    /**
     * Relasi one-to-many: Satu Role memiliki banyak User
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id','id_role');
    }
}