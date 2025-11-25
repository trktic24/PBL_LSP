<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoinPotensiAK07 extends Model
{
    use HasFactory;

    protected $table = 'poin_potensi_AK07';
    protected $primaryKey = 'id_poin_potensi_AK07';
    protected $fillable = ['deskripsi_potensi'];
}
