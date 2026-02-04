<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'picture_url' => fake()->imageUrl(),
            'description' => fake()->sentence(),
            'price' => fake()->randomNumber(4),
            'is_active' => fake()->boolean(80),
            'default_time_to_deliver' => fake()->numberBetween(1, 7),
        ];
    }
}
