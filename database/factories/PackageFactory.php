<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['name' => fake()->words(3, true), 'slug' => fake()->unique()->slug(), 'description' => fake()->sentence(), 'price' => 6999, 'sort_order' => 1, 'is_published' => true, 'is_featured' => false, 'inclusions' => []];
    }
}
