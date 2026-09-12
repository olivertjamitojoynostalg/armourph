<?php

namespace App\Console\Commands;

use App\Models\Package;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('catalog:generate-package-descriptions {--force : Replace existing descriptions}')]
#[Description('Generate cautious customer-facing descriptions from package names')]
class GeneratePackageDescriptions extends Command
{
    public function handle(): int
    {
        $query = Package::query();

        if (! $this->option('force')) {
            $query->where(fn ($query) => $query->whereNull('description')->orWhere('description', ''));
        }

        $updated = 0;
        $query->eachById(function (Package $package) use (&$updated): void {
            $package->update(['description' => $this->descriptionFor($package)]);
            $updated++;
        });

        $this->info("Generated descriptions for {$updated} packages.");

        return self::SUCCESS;
    }

    private function descriptionFor(Package $package): string
    {
        $details = [];

        if (preg_match('/\b(9|10|13)\s*["\x{201D}\']/u', $package->name, $screenMatch) === 1) {
            $details[] = $screenMatch[1].'-inch display';
        }

        if (preg_match('/(\d+)\s*(?:gb)?\s*\/\s*(\d+)\s*(?:gb)?/i', $package->name, $memoryMatch) === 1
            || preg_match('/(\d+)\s*gb\s*ram\s*\/\s*(\d+)\s*gb\s*rom/i', $package->name, $memoryMatch) === 1) {
            $details[] = $memoryMatch[1].'GB RAM';
            $details[] = $memoryMatch[2].'GB storage';
        }

        $description = $details === []
            ? 'An in-car multimedia upgrade package based on the listed configuration.'
            : 'An in-car multimedia upgrade package with '.$this->naturalList($details).'.';

        $features = [];
        $without360 = preg_match('/(?:w\s*\/\s*not|without|w\s*\/\s*o|no)\s*360/i', $package->name) === 1;
        if (! $without360 && str_contains(strtolower($package->name), '360')) {
            $features[] = '360-camera capability';
        }
        if (str_contains(strtolower($package->name), 'voice')) {
            $features[] = 'voice-command support';
        }
        if (preg_match('/\b4g\b/i', $package->name) === 1) {
            $features[] = '4G capability';
        }
        if (str_contains(strtolower($package->name), 'qled')) {
            $features[] = 'a QLED display';
        }

        if ($features !== []) {
            $description .= ' The listed configuration also features '.$this->naturalList($features).'.';
        }

        return $description.' Confirm the exact head unit, cameras, panel, wiring, and installation inclusions with your Armour branch.';
    }

    /** @param list<string> $items */
    private function naturalList(array $items): string
    {
        if (count($items) === 1) {
            return $items[0];
        }

        $lastItem = array_pop($items);

        return implode(', ', $items).' and '.$lastItem;
    }
}
