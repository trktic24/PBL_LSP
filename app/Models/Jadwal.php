<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema');
    }
}
