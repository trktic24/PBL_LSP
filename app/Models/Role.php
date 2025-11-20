<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    // Kasih tau nama tabel & PK-nya
    protected $table = 'roles';
    protected $primaryKey = 'id_role';
    protected $guarded = [];

    // Relasi: 1 Role dipake banyak User
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id_role');
    }
}