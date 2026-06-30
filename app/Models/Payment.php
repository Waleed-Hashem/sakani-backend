<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
     use HasFactory;
        protected $fillable = [
        'user_id', 'subscription_id',
        'amount', 'date', 'payment_method', 'status'
    ];

    // دفعة → تنتمي لمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // دفعة → تنتمي لاشتراك
    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }
}
