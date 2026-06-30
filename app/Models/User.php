<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role'
    ];

    protected $hidden = [
        'password'
    ];

    // مستخدم واحد → عقارات كثيرة
    public function realEstates()
    {
        return $this->hasMany(RealEstate::class, 'owner_id');
    }

    // مستخدم واحد → اشتراكات كثيرة
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    // مستخدم واحد → مدفوعات كثيرة
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // مستخدم واحد → تقييمات كثيرة
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
