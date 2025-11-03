<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunciIA06 extends Model
{
    use HasFactory;

    protected $table = 'kunci_IA06';
    protected $primaryKey = 'id_kunci_IA06';
    protected $fillable = ['id_soal_IA06', 'kunci_IA06'];

    public function soal()
    {
        return $this->belongsTo(SoalIA06::class, 'id_soal_IA06');
    }
}
