<?php

namespace Database\Factories;

use App\Models\HeroSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'eyebrow' => 'Drive smarter',
            'title' => fake()->sentence(4),
            'accent' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'desktop_image_path' => 'assets/samples/banner-desktop.png',
            'tablet_image_path' => null,
            'mobile_image_path' => 'assets/samples/banner-mobile.png',
            'image_alt' => 'Armour hero banner',
            'show_content' => false,
            'button_label' => 'Explore products',
            'button_url' => '/products',
            'content_position' => 'left',
            'sort_order' => 1,
            'is_published' => true,
        ];
    }
}
