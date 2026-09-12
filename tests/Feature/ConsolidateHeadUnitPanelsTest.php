<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConsolidateHeadUnitPanelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_consolidates_panel_variants_by_vehicle_model(): void
    {
        Storage::fake('local');
        $type = ProductType::query()->create(['name' => 'Head Unit Panel']);

        Product::factory()->create([
            'product_type_id' => $type->id,
            'name' => 'Vios Gen 1 2002-2006',
            'slug' => 'vios-gen-1',
            'image_path' => 'catalog/vios-one.jpg',
            'is_published' => true,
        ]);
        Product::factory()->create([
            'product_type_id' => $type->id,
            'name' => 'Vios Gen 2 2007-2012',
            'slug' => 'vios-gen-2',
            'image_path' => 'catalog/vios-two.jpg',
            'is_published' => true,
        ]);
        Product::factory()->create([
            'product_type_id' => $type->id,
            'name' => 'Civic FC 2016-2020',
            'slug' => 'civic-fc',
            'is_published' => true,
        ]);

        $this->artisan('catalog:consolidate-panels')->assertSuccessful();

        $this->assertDatabaseCount('products', 2);
        $vios = Product::query()->where('name', 'Toyota Vios Panel')->firstOrFail();
        $this->assertSame('catalog/vios-one.jpg', $vios->image_path);
        $this->assertSame(['catalog/vios-two.jpg'], $vios->gallery_images);
        $this->assertStringContainsString('Vios Gen 1 2002-2006', $vios->description);
        $this->assertStringContainsString('Vios Gen 2 2007-2012', $vios->description);
        $this->assertStringContainsString('GPS antenna', $vios->description);
        $this->assertSame('Includes: Head unit panel · GPS antenna · USB port · Power harness', $vios->specification);
        $this->assertDatabaseHas('products', ['name' => 'Honda Civic Panel']);
        $this->assertCount(1, Storage::disk('local')->files('catalog-backups'));
    }
}
