<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterFormTemplate extends Model
{
    use HasFactory;

    protected $table = 'master_form_templates';

    protected $fillable = [
        'id_skema',
        'form_code',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
    }
}
