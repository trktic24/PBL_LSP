<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisTuk extends Model
{
    use HasFactory;
    protected $table = 'jenis_tuk';
    protected $primaryKey = 'id_jenis_tuk';
    public $timestamps=false;
    protected $fillable = [
        'sewaktu',
        'tempat_kerja',
        'mandiri'
    ];


    // public function jadwal(): HasMany
    // {
    //     return $this->hasMany(Jadwal::class, 'id_jenis_tuk', 'id_jenis_tuk');
    // }
}