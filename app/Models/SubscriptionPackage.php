<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPackage extends Model
{
     use HasFactory;
    //
        protected $fillable = ['name', 'price', 'duration'];

    // باقة → لها اشتراكات كثيرة
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'package_id');
    }
}
