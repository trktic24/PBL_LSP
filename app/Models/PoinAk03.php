<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinAk03 extends Model
{
    use HasFactory;
    protected $table = 'poin_ak03';
    protected $primaryKey = 'id_poin_ak03';
    protected $fillable = ['komponen'];
}
