<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** @var array<string, array{latitude: float, longitude: float}> */
    private const BRANCH_COORDINATES = [
        'Armour Mindanao Avenue' => ['latitude' => 14.6711733, 'longitude' => 121.0325169],
        'Armour Pampanga' => ['latitude' => 15.1496050, 'longitude' => 120.5995075],
        'Armour Cebu' => ['latitude' => 10.3090000, 'longitude' => 123.8930000],
        'Armour San Juan Branch' => ['latitude' => 14.6128000, 'longitude' => 121.0248000],
    ];

    public function up(): void
    {
        foreach (self::BRANCH_COORDINATES as $name => $coordinates) {
            DB::table('branches')
                ->where('name', $name)
                ->whereNull('latitude')
                ->whereNull('longitude')
                ->update($coordinates);
        }
    }

    public function down(): void
    {
        foreach (self::BRANCH_COORDINATES as $name => $coordinates) {
            DB::table('branches')
                ->where('name', $name)
                ->where('latitude', $coordinates['latitude'])
                ->where('longitude', $coordinates['longitude'])
                ->update(['latitude' => null, 'longitude' => null]);
        }
    }
};
