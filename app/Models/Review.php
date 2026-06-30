<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // تحديد الحقول المسموح بتعبئتها في قاعدة البيانات لحماية النظام
    protected $fillable = [
        'user_id',
        'owner_id',
        'rating',
        'comment'
    ];

    // علاقة التقييم بالزبون الذي كتبه
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة التقييم بصاحب العقار المشتكى عليه
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
