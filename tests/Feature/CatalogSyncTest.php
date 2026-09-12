<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CatalogSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_sync_source_data_while_preserving_public_content(): void
    {
        config([
            'services.advance_auto.url' => 'https://advance.test',
            'services.advance_auto.token' => 'shared-token',
        ]);

        $oldType = ProductType::query()->create(['name' => 'Old type']);
        Product::factory()->create([
            'source_id' => 10,
            'product_type_id' => $oldType->id,
            'name' => 'Curated camera name',
            'slug' => 'curated-camera',
            'description' => 'Curated description',
            'image_path' => 'storage/products/camera.jpg',
            'price' => 1000,
            'is_published' => true,
        ]);
        Product::factory()->create(['source_id' => 11, 'name' => 'Inactive product', 'slug' => 'inactive-product', 'is_published' => true]);
        Product::factory()->create(['source_id' => 99, 'name' => 'Removed product', 'slug' => 'removed-product', 'is_published' => true]);

        Package::factory()->create([
            'source_id' => 20,
            'name' => 'Curated package name',
            'slug' => 'curated-package',
            'description' => 'Curated package description',
            'image_path' => 'storage/packages/package.jpg',
            'price' => 5000,
            'is_published' => true,
        ]);
        Package::factory()->create(['source_id' => 98, 'name' => 'Removed package', 'slug' => 'removed-package', 'is_published' => true]);

        Branch::query()->create([
            'source_id' => 30,
            'name' => 'Old branch name',
            'address' => 'Old address',
            'contact' => null,
            'hours' => '9 AM–6 PM',
            'url' => 'https://maps.example/branch',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Http::fake(['advance.test/*' => Http::response([
            'version' => 1,
            'products' => [
                ['source_id' => 10, 'name' => 'INTERNAL-CAMERA', 'type' => 'Dash Cameras', 'price' => 3999, 'is_active' => true],
                ['source_id' => 11, 'name' => 'INTERNAL-INACTIVE', 'type' => 'Dash Cameras', 'price' => 4999, 'is_active' => false],
                ['source_id' => 12, 'name' => 'INTERNAL-NEW', 'type' => 'Accessories', 'price' => 899, 'is_active' => true],
            ],
            'packages' => [
                ['source_id' => 20, 'name' => 'INTERNAL-PACKAGE', 'price' => 7999],
                ['source_id' => 21, 'name' => 'INTERNAL-NEW-PACKAGE', 'price' => 9999],
            ],
            'branches' => [
                ['source_id' => 30, 'name' => 'Quezon City', 'address' => 'New address', 'contact' => '09170000000', 'is_active' => true, 'is_warehouse' => false],
                ['source_id' => 31, 'name' => 'Warehouse', 'address' => 'Warehouse address', 'contact' => null, 'is_active' => true, 'is_warehouse' => true],
                ['source_id' => 32, 'name' => 'Closed branch', 'address' => 'Closed address', 'contact' => null, 'is_active' => false, 'is_warehouse' => false],
            ],
        ])]);

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post(route('admin.sync'))
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $curatedProduct = Product::query()->where('source_id', 10)->firstOrFail();
        $this->assertSame('Curated camera name', $curatedProduct->name);
        $this->assertSame('Curated description', $curatedProduct->description);
        $this->assertSame('storage/products/camera.jpg', $curatedProduct->image_path);
        $this->assertSame('3999.00', $curatedProduct->price);
        $this->assertSame('Dash Cameras', $curatedProduct->productType?->name);
        $this->assertTrue($curatedProduct->is_published);
        $this->assertFalse(Product::query()->where('source_id', 11)->firstOrFail()->is_published);
        $this->assertFalse(Product::query()->where('source_id', 99)->firstOrFail()->is_published);
        $this->assertFalse(Product::query()->where('source_id', 12)->firstOrFail()->is_published);

        $curatedPackage = Package::query()->where('source_id', 20)->firstOrFail();
        $this->assertSame('Curated package name', $curatedPackage->name);
        $this->assertSame('Curated package description', $curatedPackage->description);
        $this->assertSame('storage/packages/package.jpg', $curatedPackage->image_path);
        $this->assertSame('7999.00', $curatedPackage->price);
        $this->assertFalse(Package::query()->where('source_id', 98)->firstOrFail()->is_published);
        $this->assertFalse(Package::query()->where('source_id', 21)->firstOrFail()->is_published);

        $this->assertDatabaseHas('branches', [
            'source_id' => 30, 'name' => 'Quezon City', 'address' => 'New address', 'contact' => '09170000000',
            'hours' => '9 AM–6 PM', 'url' => 'https://maps.example/branch', 'is_published' => true,
        ]);
        $this->assertDatabaseHas('branches', ['source_id' => 32, 'is_published' => false]);
        $this->assertDatabaseMissing('branches', ['source_id' => 31]);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://advance.test/api/v1/website-catalog'
            && $request->hasHeader('Authorization', 'Bearer shared-token'));
    }

    public function test_sync_requires_configuration_and_admin_access(): void
    {
        $this->post(route('admin.sync'))->assertRedirect(route('login'));

        config(['services.advance_auto.url' => null, 'services.advance_auto.token' => null]);
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post(route('admin.sync'))->assertSessionHasErrors('sync');
    }
}
