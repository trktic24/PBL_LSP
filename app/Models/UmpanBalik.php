<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Feedback extends Model
{
    use HasFactory;


    protected $table = 'feedback';
    protected $fillable = ['id_asesi', 'id_asesor', 'tuk', 'catatan_tambahan'];


    public function items()
    {
        return $this->hasMany(FeedbackItem::class);
    }


    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'id_asesi');
    }


    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'id_asesor');
    }
}