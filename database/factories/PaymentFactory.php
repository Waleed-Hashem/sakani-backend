<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\UserSubscription;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
        'user_id'         => User::factory(),
        'subscription_id' => UserSubscription::factory(),
        'amount'          => fake()->randomFloat(2, 10, 500),
        'date'            => fake()->dateTimeBetween('-1 year', 'now'),
        'payment_method'  => fake()->randomElement([
                                 'credit_card', 'paypal', 'bank_transfer'
                             ]),
        'status'          => fake()->randomElement(['success', 'failed']),
    ];
    }
}
