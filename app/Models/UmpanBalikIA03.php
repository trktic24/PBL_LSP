<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmpanBalikIA03 extends Model
{
    protected $table = 'umpan_balik_ia03';
    protected $primaryKey = 'id_umpan_balik_ia03';
    
    protected $fillable = [
        'id_ia03',
        'umpan_balik'
    ];

    public function ia03()
    {
        return $this->belongsTo(IA03::class, 'id_ia03', 'id_ia03');
    }
}