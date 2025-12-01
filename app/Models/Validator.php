<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validator extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'validator';
    protected $primaryKey = 'id_validator';

    protected $fillable = [
        'nama_validator',
        'no_MET_validator',
        'ttd',
    ];
}