<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\SubscriptionPackage;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSubscription>
 */
class UserSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
        'user_id'    => User::factory(),
        'package_id' => SubscriptionPackage::factory(),
        'status'     => fake()->randomElement([
                            'active', 'expired', 'cancelled'
                        ]),
    ];
    }
}
