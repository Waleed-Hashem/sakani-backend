<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    // ========== Relationships ==========

    // الاشتراك → ينتمي لمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الاشتراك → ينتمي لباقة
    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id');
    }

    // الاشتراك → له مدفوعات
    public function payments()
    {
        return $this->hasMany(Payment::class, 'subscription_id');
    }

    // ========== Helpers ==========

    // هل الاشتراك فعّال؟
    public function isActive()
    {
        return $this->status === 'active'
            && $this->ends_at
            && $this->ends_at->isFuture();
    }

    // هل الاشتراك منتهي؟
    public function isExpired()
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    // كم يوم متبقي؟
    public function daysLeft()
    {
        return $this->ends_at
            ? now()->diffInDays($this->ends_at, false)
            : 0;
    }

    // ========== Static Subscribe Method ==========

    public static function subscribe(int $userId, int $packageId, float $amount): self
    {
        return DB::transaction(function () use ($userId, $packageId, $amount) {

            $package = SubscriptionPackage::findOrFail($packageId);

            // تحديد مدة الاشتراك
            $startsAt = now();
            $endsAt = $package->duration === 'monthly'
                ? now()->addMonth()
                : now()->addYear();

            // الخطوة 1: إنشاء الاشتراك
            $subscription = self::create([
                'user_id'    => $userId,
                'package_id' => $packageId,
                'status'     => 'active',
                'starts_at'  => $startsAt,
                'ends_at'    => $endsAt,
            ]);

            // الخطوة 2: تسجيل الدفع
            Payment::create([
                'user_id'         => $userId,
                'subscription_id' => $subscription->id,
                'amount'          => $amount,
                'date'            => now(),
                'payment_method'  => 'credit_card',
                'status'          => 'success',
            ]);

            return $subscription;
        });
    }
}
