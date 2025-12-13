<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ia10 extends Model
{
    use HasFactory;
    
    protected $table = 'ia10';
    protected $primaryKey = 'id_ia10';
    protected $guarded = [];

    // Relasi ke Detail (Essay)
    public function details()
    {
        return $this->hasMany(DetailIa10::class, 'id_ia10', 'id_ia10');
    }
}
