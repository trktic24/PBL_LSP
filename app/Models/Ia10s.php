<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ia10s extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel & primary key
    protected $table = 'ia10s';
    protected $primaryKey = 'id';

    // 2. Tentukan semua kolom yang BOLEH diisi
    // (Ini harus cocok 100% dengan screenshot Anda)
    protected $fillable = [
        'nama_asesi',
        'nama_asesor',
        'supervisor_name',
        'workplace',
        'address',
        'phone',
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'q6',
        'relation',
        'duration',
        'proximity',
        'experience',
        'consistency',
        'training_needs',
        'other_comments',
    ];
}