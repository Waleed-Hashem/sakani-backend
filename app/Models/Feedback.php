<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
     use HasFactory;
    //
         protected $table = 'feedbacks';
        protected $fillable = [
        'user_id', 'type', 'content', 'city', 'area'
    ];

    // تقييم → ينتمي لمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
