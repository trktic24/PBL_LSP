<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $table = 'skema';

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_skema');
    }
}
