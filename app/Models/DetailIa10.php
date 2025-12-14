<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailIa10 extends Model
{
    use HasFactory;

    protected $table = 'detail_ia10';
    protected $primaryKey = 'id_detail_ia10';
    protected $guarded = [];

    protected $fillable = [
        'id_ia10',
        'isi_detail', // Kita akan simpan Label Pertanyaan di sini
        'jawaban',    // Kita simpan Input User di sini
    ];
}