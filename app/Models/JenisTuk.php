<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTuk extends Model
{
    use HasFactory;

    /**
     * (WAJIB) Memberi tahu Laravel nama tabel yang benar.
     */
    protected $table = 'jenis_tuk';

    /**
     * (WAJIB) Memberi tahu Laravel primary key yang benar.
     */
    protected $primaryKey = 'id_jenis_tuk';

    /**
     * Izinkan mass assignment.
     */
    protected $guarded = [];

    
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_jenis_tuk', 'id_jenis_tuk');
    }
}