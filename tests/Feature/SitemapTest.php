<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_contains_public_catalog_pages_only(): void
    {
        $publishedProduct = Product::factory()->create(['is_published' => true]);
        $hiddenProduct = Product::factory()->create(['is_published' => false]);
        $publishedPackage = Package::factory()->create(['is_published' => true]);
        $hiddenPackage = Package::factory()->create(['is_published' => false]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('home'), false)
            ->assertSee(route('products.index'), false)
            ->assertSee(route('packages.index'), false)
            ->assertSee(route('products.show', $publishedProduct), false)
            ->assertSee(route('packages.show', $publishedPackage), false)
            ->assertDontSee(route('products.show', $hiddenProduct), false)
            ->assertDontSee(route('packages.show', $hiddenPackage), false);
    }
}
