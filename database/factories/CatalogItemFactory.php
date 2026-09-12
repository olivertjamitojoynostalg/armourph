<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogItemFactory extends Factory
{
    public function definition(): array
    {
        return ['kind' => 'product', 'name' => fake()->words(3, true), 'slug' => fake()->unique()->slug(), 'description' => fake()->sentence(), 'price' => 1500, 'sort_order' => 1, 'is_published' => true, 'inclusions' => []];
    }
}
