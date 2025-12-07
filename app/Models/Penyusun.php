<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyusun extends Model
{
    use HasFactory;

    protected $table = 'penyusun';
    protected $primaryKey = 'id_penyusun';

    protected $fillable = [
        'penyusun',
        'no_MET_penyusun',
        'ttd',
    ];

    public function penyusunValidators()
    {
        return $this->hasMany(PenyusunValidator::class, 'id_penyusun');
    }
}
