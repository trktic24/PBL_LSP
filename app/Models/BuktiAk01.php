<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiAk01 extends Model
{
    use HasFactory;

    protected $table = 'bukti_ak01';
    protected $primaryKey = 'id_bukti_ak01'; // Penting karena bukan 'id'
    protected $guarded = [];

    // Izinkan kolom-kolom ini diisi saat 'create'
    protected $fillable = [
        'bukti',
    ];    
}
