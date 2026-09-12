<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneratePackageInclusionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_only_explicit_inclusions_and_preserves_curated_content(): void
    {
        $package = Package::factory()->create(['name' => '9" 4/64GB with 360', 'inclusions' => null]);
        $without360 = Package::factory()->create(['name' => '10" 4/64GB w/not 360', 'inclusions' => null]);
        $curatedPackage = Package::factory()->create(['inclusions' => ['Curated installation item']]);

        $this->artisan('catalog:generate-package-inclusions')->assertSuccessful();

        $this->assertSame(['9-inch multimedia head unit', '360 camera setup'], $package->refresh()->inclusions);
        $this->assertSame(['10-inch multimedia head unit'], $without360->refresh()->inclusions);
        $this->assertSame(['Curated installation item'], $curatedPackage->refresh()->inclusions);
    }
}
