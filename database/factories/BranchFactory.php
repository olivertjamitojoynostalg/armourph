<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Branch',
            'address' => fake()->address(),
            'contact' => fake()->phoneNumber(),
            'hours' => 'Monday–Sunday, 9:00 AM–6:00 PM',
            'url' => null,
            'embed_url' => null,
            'latitude' => null,
            'longitude' => null,
            'sort_order' => 1,
            'is_published' => true,
        ];
    }
}
