<?php

namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RealEstate>
 */
class RealEstateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
      public function definition(): array
    {
       return [
        'type'     => fake()->randomElement(['apartment', 'land']),
        'city'     => fake()->randomElement([
                          'Damascus', 'Aleppo', 'Homs', 'Latakia'
                      ]),
        'area'     => fake()->randomElement([
                          'Mezzeh', 'Malki', 'Kafr Sousa', 'Bab Touma'
                      ]),
        'address'  => fake()->address(),
        'price'    => fake()->numberBetween(50000, 500000),
        'status'   => fake()->randomElement(['for_sale', 'for_rent']),
        'owner_id' => User::factory(),           // ينشئ مستخدم تلقائياً
      ];
    }
}
