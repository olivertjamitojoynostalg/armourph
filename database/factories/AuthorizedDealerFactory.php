<?php

namespace Database\Factories;

use App\Models\AuthorizedDealer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuthorizedDealer>
 */
class AuthorizedDealerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'image_path' => 'dealers/example.jpg',
            'sort_order' => 1,
            'is_published' => true,
        ];
    }
}
