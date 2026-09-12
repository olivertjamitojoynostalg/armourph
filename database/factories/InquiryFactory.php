<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'branch_name' => 'Armour Branch',
            'name' => fake()->name(),
            'contact' => '09'.fake()->numerify('#########'),
            'interest' => fake()->randomElement(['products', 'packages', 'installation', 'general']),
            'status' => 'new',
            'internal_notes' => null,
            'ip_hash' => hash('sha256', fake()->ipv4()),
        ];
    }
}
