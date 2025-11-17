<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FeedbackItem extends Model
{
    use HasFactory;


    protected $table = 'feedback_items';
    protected $fillable = ['feedback_id', 'nomor', 'pernyataan', 'ya', 'tidak', 'catatan'];


    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
}