<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    protected $table = 'asesor';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }    
}
