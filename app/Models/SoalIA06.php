<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalIA06 extends Model
{
    use HasFactory;

    protected $table = 'soal_IA06';
    protected $primaryKey = 'id_soal_IA06';
    protected $fillable = ['soal_IA06'];

    public function kuncis()
    {
        return $this->hasMany(KunciIA06::class, 'id_soal_IA06');
    }
}
