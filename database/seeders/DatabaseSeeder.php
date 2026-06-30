<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RealEstate;
use App\Models\SubscriptionPackage;
use App\Models\UserSubscription;
use App\Models\Payment;
use App\Models\Feedback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
           
       public function run(): void
{
    // 1. أنشئ 10 مستخدمين
    $users = User::factory(10)->create();

    // 2. كل مستخدم له 2 عقار
    $users->each(function ($user) {
        RealEstate::factory(2)->create(['owner_id' => $user->id]);
    });

    // 3. أنشئ 3 باقات اشتراك
    $packages = SubscriptionPackage::factory(3)->create();

    // 4. أنشئ 10 اشتراكات مرتبطة بمستخدمين وباقات موجودة
    $subscriptions = UserSubscription::factory(10)->create([
        'user_id'    => fn() => $users->random()->id,
        'package_id' => fn() => $packages->random()->id,
    ]);

    // 5. أنشئ 10 مدفوعات
    Payment::factory(10)->create([
        'user_id'         => fn() => $users->random()->id,
        'subscription_id' => fn() => $subscriptions->random()->id,
    ]);

    // 6. أنشئ 15 تقييم وشكوى
    Feedback::factory(15)->create([
        'user_id' => fn() => $users->random()->id,
    ]);
}
}
