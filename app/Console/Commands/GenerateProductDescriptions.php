<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('catalog:generate-product-descriptions {--force : Replace existing descriptions}')]
#[Description('Generate cautious customer-facing descriptions from the product catalog')]
class GenerateProductDescriptions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $query = Product::query()->with('productType');

        if (! $this->option('force')) {
            $query->where(fn ($query) => $query->whereNull('description')->orWhere('description', ''));
        }

        $updated = 0;
        $query->eachById(function (Product $product) use (&$updated): void {
            $product->update(['description' => $this->descriptionFor($product)]);
            $updated++;
        });

        $this->info("Generated descriptions for {$updated} products.");

        return self::SUCCESS;
    }

    private function descriptionFor(Product $product): string
    {
        $name = trim($product->name);
        $type = Str::lower(trim($product->productType?->name ?? ''));
        $specification = trim($product->specification ?? '');

        $description = match (true) {
            $type === 'panel' => "A vehicle-specific dashboard panel for {$name}, designed to support a clean head-unit installation.",
            in_array($type, ['lcd', 'head unit'], true) => "The {$name} is an in-car multimedia head unit for compatible dashboard setups.",
            str_contains($type, 'dashcam') => "The {$name} is an in-car camera designed to help capture video during your drive.",
            $type === 'camera' => "The {$name} adds camera visibility to a compatible in-car display.",
            str_contains($type, 'speaker') => "The {$name} is an automotive audio speaker designed for compatible in-car sound systems.",
            str_contains($type, 'subwoofer') => "The {$name} adds low-frequency sound to a compatible in-car audio system.",
            str_contains($type, 'tint') => "The {$name} is an automotive window film option for added comfort and privacy.",
            $type === 'headlight' => "The {$name} is an automotive lighting option for compatible headlight assemblies.",
            $type === 'horn' => "The {$name} is an automotive horn option for compatible vehicle electrical systems.",
            $type === 'parking sensor' => "The {$name} is a parking-assistance accessory that helps alert the driver to nearby obstacles.",
            in_array($type, ['monitor', 'headrest', 'headrest monitor'], true) => "The {$name} adds an in-car display for compatible entertainment setups.",
            $type === 'steering wheel' => "The {$name} is a steering-wheel upgrade for compatible vehicle applications.",
            $type === 'dsp box' => "The {$name} is an audio signal-processing component for compatible in-car sound systems.",
            $type === 'wire' => "The {$name} is an installation component for compatible automotive accessory setups.",
            $type === 'mags center cap' => "The {$name} is a replacement center-cap option for compatible wheels.",
            in_array($type, ['labor', 'fee'], true) => "The {$name} covers the listed service or installation requirement.",
            default => "The {$name} is an automotive accessory for compatible vehicle applications.",
        };

        if ($specification !== '') {
            $description .= " Listed specification: {$specification}.";
        }

        return $description.' Confirm exact fitment, included parts, and installation requirements with your Armour branch.';
    }
}
