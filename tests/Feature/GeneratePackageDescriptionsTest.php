<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneratePackageDescriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_missing_descriptions_without_replacing_curated_copy(): void
    {
        $package = Package::factory()->create([
            'name' => '9" 4/64GB with 360 and voice 4G QLED',
            'description' => '',
        ]);
        $curatedPackage = Package::factory()->create(['description' => 'Keep this curated description.']);

        $this->artisan('catalog:generate-package-descriptions')->assertSuccessful();

        $this->assertSame(
            'An in-car multimedia upgrade package with 9-inch display, 4GB RAM and 64GB storage. The listed configuration also features 360-camera capability, voice-command support, 4G capability and a QLED display. Confirm the exact head unit, cameras, panel, wiring, and installation inclusions with your Armour branch.',
            $package->refresh()->description,
        );
        $this->assertSame('Keep this curated description.', $curatedPackage->refresh()->description);
    }
}
