<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateProductDescriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_missing_descriptions_without_replacing_curated_copy(): void
    {
        $panelType = ProductType::query()->create(['name' => 'Panel']);
        $product = Product::factory()->create([
            'product_type_id' => $panelType->id,
            'name' => 'Vios 2019–2023 Panel',
            'description' => null,
        ]);
        $curatedProduct = Product::factory()->create(['description' => 'Keep this curated description.']);

        $this->artisan('catalog:generate-product-descriptions')->assertSuccessful();

        $this->assertSame(
            'A vehicle-specific dashboard panel for Vios 2019–2023 Panel, designed to support a clean head-unit installation. Confirm exact fitment, included parts, and installation requirements with your Armour branch.',
            $product->refresh()->description,
        );
        $this->assertSame('Keep this curated description.', $curatedProduct->refresh()->description);
    }
}
