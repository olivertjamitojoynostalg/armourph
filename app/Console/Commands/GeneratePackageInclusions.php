<?php

namespace App\Console\Commands;

use App\Models\Package;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('catalog:generate-package-inclusions {--force : Replace existing inclusions}')]
#[Description('Generate conservative package inclusions from package names')]
class GeneratePackageInclusions extends Command
{
    public function handle(): int
    {
        $query = Package::query();

        if (! $this->option('force')) {
            $query->where(fn ($query) => $query->whereNull('inclusions')->orWhereJsonLength('inclusions', 0));
        }

        $updated = 0;
        $query->eachById(function (Package $package) use (&$updated): void {
            $package->update(['inclusions' => $this->inclusionsFor($package)]);
            $updated++;
        });

        $this->info("Generated inclusions for {$updated} packages.");

        return self::SUCCESS;
    }

    /** @return list<string> */
    private function inclusionsFor(Package $package): array
    {
        $name = $package->name;
        $headUnit = 'Multimedia head unit';

        if (preg_match('/\b(9|10|13)\s*["\x{201D}\']/u', $name, $screenMatch) === 1) {
            $headUnit = $screenMatch[1].'-inch multimedia head unit';
        } elseif (str_contains(strtolower($name), 'explorer')) {
            $headUnit = 'Explorer-compatible multimedia head unit';
        }

        $inclusions = [$headUnit];
        $without360 = preg_match('/(?:w\s*\/\s*not|without|w\s*\/\s*o|no)\s*360/i', $name) === 1;

        if (! $without360 && str_contains(strtolower($name), '360')) {
            $inclusions[] = '360 camera setup';
        }

        return $inclusions;
    }
}
