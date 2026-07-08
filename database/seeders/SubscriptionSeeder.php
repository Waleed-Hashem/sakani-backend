<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPackage;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        SubscriptionPackage::create([
            'name' => 'الباقة الشهرية الأساسية',
            'price' => 50,
            'duration' => 'monthly'
        ]);

        SubscriptionPackage::create([
            'name' => 'الباقة الشهرية المتقدمة',
            'price' => 120,
            'duration' => 'monthly'
        ]);

        SubscriptionPackage::create([
            'name' => 'الباقة السنوية الذهبية',
            'price' => 250,
            'duration' => 'yearly'
        ]);
    }
}
