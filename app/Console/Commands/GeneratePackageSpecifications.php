<?php

namespace App\Console\Commands;

use App\Models\Package;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('catalog:generate-package-specifications {--force : Replace existing specifications}')]
#[Description('Generate package specifications from package names')]
class GeneratePackageSpecifications extends Command
{
    public function handle(): int
    {
        $query = Package::query();

        if (! $this->option('force')) {
            $query->where(fn ($query) => $query->whereNull('specification')->orWhere('specification', ''));
        }

        $updated = 0;
        $query->eachById(function (Package $package) use (&$updated): void {
            $package->update(['specification' => $this->specificationFor($package)]);
            $updated++;
        });

        $this->info("Generated specifications for {$updated} packages.");

        return self::SUCCESS;
    }

    private function specificationFor(Package $package): string
    {
        $specifications = [];
        $name = $package->name;

        if (preg_match('/\b(9|10|13)\s*["\x{201D}\']/u', $name, $match) === 1) {
            $specifications[] = $match[1].'-inch display';
        }

        if (preg_match('/(\d+)\s*(?:gb)?\s*\/\s*(\d+)\s*(?:gb)?/i', $name, $match) === 1
            || preg_match('/(\d+)\s*gb\s*ram\s*\/\s*(\d+)\s*gb\s*rom/i', $name, $match) === 1) {
            $specifications[] = $match[1].'GB RAM';
            $specifications[] = $match[2].'GB storage';
        }

        foreach (['7212B', '7870', 'TS10', 'TS18', 'M-PADMax'] as $platform) {
            if (str_contains(strtolower($name), strtolower($platform))) {
                $specifications[] = $platform.' platform';
            }
        }

        $without360 = preg_match('/(?:w\s*\/\s*not|without|w\s*\/\s*o|no)\s*360/i', $name) === 1;
        if ($without360) {
            $specifications[] = 'Without 360 camera';
        } elseif (str_contains(strtolower($name), '360')) {
            $specifications[] = '360-camera capability';
        }

        if (str_contains(strtolower($name), 'voice')) {
            $specifications[] = 'Voice command';
        }
        if (preg_match('/\b4g\b/i', $name) === 1) {
            $specifications[] = '4G capability';
        }
        if (str_contains(strtolower($name), 'qled')) {
            $specifications[] = 'QLED display';
        }
        if (str_contains(strtolower($name), 'fan')) {
            $specifications[] = 'Cooling fan';
        }

        return implode(' · ', array_unique($specifications ?: ['Configuration details available from branch']));
    }
}
