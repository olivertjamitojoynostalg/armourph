<?php

namespace Tests\Feature;

use App\Models\AuthorizedDealer;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_is_public_and_linked_from_navigation(): void
    {
        SiteSetting::query()->create(['key' => 'about', 'value' => [
            'heading' => 'A custom maintained heading',
            'body' => '<p>Custom <strong>maintained about</strong> content.</p><script>alert("unsafe")</script>',
            'reviews_heading' => 'Real customer stories',
            'review_images' => ['about-reviews/feedback-one.jpg', 'storage/about-reviews/feedback-two.png'],
        ]]);

        $this->get(route('about'))
            ->assertOk()
            ->assertViewIs('about')
            ->assertSee('A custom maintained heading')
            ->assertSee('<p>Custom <strong>maintained about</strong> content.</p>', false)
            ->assertDontSee('<script>alert("unsafe")</script>', false)
            ->assertSee('Real customer stories')
            ->assertSee('/storage/about-reviews/feedback-one.jpg', false)
            ->assertSee('/storage/about-reviews/feedback-two.png', false)
            ->assertSee('Customer feedback screenshot 2')
            ->assertDontSee('armour-hero-desktop-v1.jpg')
            ->assertSee('href="'.route('dealers').'"', false);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('href="'.route('about').'"', false);
    }

    public function test_authorized_dealers_page_only_shows_published_dealer_names_and_images(): void
    {
        AuthorizedDealer::factory()->create([
            'name' => 'Official Dealer',
            'image_path' => 'dealers/official.jpg',
            'is_published' => true,
        ]);
        AuthorizedDealer::factory()->create(['name' => 'Hidden Test Dealer', 'is_published' => false]);

        $this->get(route('dealers'))
            ->assertOk()
            ->assertViewIs('dealers')
            ->assertSee('Authorized')
            ->assertSee('Official Dealer')
            ->assertSee('/storage/dealers/official.jpg', false)
            ->assertDontSee('Hidden Test Dealer')
            ->assertDontSee('Get directions');
    }
}
