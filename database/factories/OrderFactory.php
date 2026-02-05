<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_identifier_number' => fake()->unique()->numberBetween(1, 98),
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['beérkezett', 'feldolgozás alatt', 'teljesítve', 'kész', 'lemondva']),
            'delivery_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ];
    }
}
