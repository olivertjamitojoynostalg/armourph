<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneratePackageSpecificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_missing_specs_and_preserves_curated_specs(): void
    {
        $package = Package::factory()->create(['name' => '9" 4/64GB w/not 360', 'specification' => null]);
        $curatedPackage = Package::factory()->create(['specification' => 'Curated specification']);

        $this->artisan('catalog:generate-package-specifications')->assertSuccessful();

        $this->assertSame('9-inch display · 4GB RAM · 64GB storage · Without 360 camera', $package->refresh()->specification);
        $this->assertSame('Curated specification', $curatedPackage->refresh()->specification);
    }
}
