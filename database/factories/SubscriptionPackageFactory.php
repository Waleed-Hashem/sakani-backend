<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPackage>
 */
class SubscriptionPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
        'name'     => fake()->randomElement(['Basic', 'Premium']),
        'price'    => fake()->randomElement([9.99, 19.99, 49.99, 99.99]),
        'duration' => fake()->randomElement(['monthly', 'yearly']),
        ];
    }
}
