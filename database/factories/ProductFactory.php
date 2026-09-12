<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['name' => fake()->words(3, true), 'slug' => fake()->unique()->slug(), 'price' => 1500, 'sort_order' => 1, 'is_published' => true, 'is_featured' => false];
    }
}
