<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsolidateHeadUnitPanels extends Command
{
    protected $signature = 'catalog:consolidate-panels';

    protected $description = 'Replace individual head unit panel variants with products grouped by vehicle model';

    /** @var array<string, array{0: string, 1: string}> */
    private const VEHICLE_PATTERNS = [
        'brv.*brio|br-v.*brio' => ['Honda', 'BR-V / Brio'],
        'fortuner.*hilux' => ['Toyota', 'Fortuner / Hilux'],
        'ertiga.*swift' => ['Suzuki', 'Ertiga / Swift'],
        'forester.*xv' => ['Subaru', 'Forester / XV'],
        'montero.*strada' => ['Mitsubishi', 'Montero / Strada'],
        'strada.*triton' => ['Mitsubishi', 'Strada / Triton'],
        'cross corolla|corolla cross' => ['Toyota', 'Corolla Cross'],
        'toyota 86' => ['Toyota', '86'], 'toyota bb' => ['Toyota', 'BB'],
        'trail ?blazer' => ['Chevrolet', 'Trailblazer'],
        'mazda cx9' => ['Mazda', 'CX-9'], 'mazda cx7|\bcx7\b' => ['Mazda', 'CX-7'],
        'mazda cx5' => ['Mazda', 'CX-5'], 'mazda 6' => ['Mazda', '6'],
        'mazda 3' => ['Mazda', '3'], 'mazda 2' => ['Mazda', '2'], '\bbt50\b' => ['Mazda', 'BT-50'],
        '\baccord\b' => ['Honda', 'Accord'], '\bbrv\b|\bbr-v\b' => ['Honda', 'BR-V'],
        '\bbrio\b' => ['Honda', 'Brio'], '\bcrv\b|\bcr-v\b' => ['Honda', 'CR-V'],
        '\bcity\b' => ['Honda', 'City'], '\bcivic\b' => ['Honda', 'Civic'],
        '\bhrv\b|\bhr-v\b' => ['Honda', 'HR-V'], '\bjazz\b' => ['Honda', 'Jazz'],
        '\basx\b' => ['Mitsubishi', 'ASX'], '\bexpander\b|\bxpander\b' => ['Mitsubishi', 'Xpander'],
        '\blancer\b' => ['Mitsubishi', 'Lancer'], '\bmirage\b' => ['Mitsubishi', 'Mirage'],
        '\bmontero\b' => ['Mitsubishi', 'Montero Sport'], '\boutlander\b' => ['Mitsubishi', 'Outlander'],
        '\bpajero\b' => ['Mitsubishi', 'Pajero'], '\bstrada\b' => ['Mitsubishi', 'Strada'],
        '\balphard\b' => ['Toyota', 'Alphard'], '\baltis\b' => ['Toyota', 'Corolla Altis'],
        '\bavanza\b' => ['Toyota', 'Avanza'], '\bcamry\b' => ['Toyota', 'Camry'],
        '\bfj cruiser\b' => ['Toyota', 'FJ Cruiser'], '\bfortuner\b' => ['Toyota', 'Fortuner'],
        '\bhiace\b' => ['Toyota', 'Hiace'], '\bhilux\b' => ['Toyota', 'Hilux'],
        '\binnova\b' => ['Toyota', 'Innova'], '\blc200\b' => ['Toyota', 'Land Cruiser 200'],
        '\bprado\b' => ['Toyota', 'Land Cruiser Prado'], '\braize\b' => ['Toyota', 'Raize'],
        '\brav ?4\b' => ['Toyota', 'RAV4'], '\brush\b' => ['Toyota', 'Rush'],
        '\btamarr?aw\b' => ['Toyota', 'Tamaraw'], '\bveloz\b' => ['Toyota', 'Veloz'],
        '\bvios\b' => ['Toyota', 'Vios'], '\bwigo\b' => ['Toyota', 'Wigo'], '\byaris\b' => ['Toyota', 'Yaris'],
        '\baccent\b' => ['Hyundai', 'Accent'], '\bcreta\b' => ['Hyundai', 'Creta'],
        '\belantra\b' => ['Hyundai', 'Elantra'], '\beon\b' => ['Hyundai', 'Eon'],
        '\bgenesis\b' => ['Hyundai', 'Genesis'], '\bh350\b' => ['Hyundai', 'H350'],
        '\bkona\b' => ['Hyundai', 'Kona'], '\breina\b|\briena\b' => ['Hyundai', 'Reina'],
        '\bsanta fe\b' => ['Hyundai', 'Santa Fe'], '\bstarex\b|^grand$' => ['Hyundai', 'Starex'],
        '\bstargazer\b' => ['Hyundai', 'Stargazer'], '\btucson\b' => ['Hyundai', 'Tucson'],
        '\balmera\b' => ['Nissan', 'Almera'], '\bjuke\b' => ['Nissan', 'Juke'],
        '\bnv350\b' => ['Nissan', 'NV350 Urvan'], '\bnavara\b' => ['Nissan', 'Navara'],
        '\bpatrol\b' => ['Nissan', 'Patrol'], '\bterra\b' => ['Nissan', 'Terra'],
        '\bx-?trail\b' => ['Nissan', 'X-Trail'], '\by10\b' => ['Nissan', 'Y10'],
        '\bcaptiva\b' => ['Chevrolet', 'Captiva'], '\bcruze\b' => ['Chevrolet', 'Cruze'],
        '\bsail\b' => ['Chevrolet', 'Sail'], '\bsonic\b' => ['Chevrolet', 'Sonic'],
        '\bcarry\b' => ['Suzuki', 'Carry'], '\bcelerio\b' => ['Suzuki', 'Celerio'],
        '\bciaz\b' => ['Suzuki', 'Ciaz'], '\bdzire\b' => ['Suzuki', 'Dzire'],
        '\bertiga\b' => ['Suzuki', 'Ertiga'], '\bespresso\b' => ['Suzuki', 'S-Presso'],
        '\bjimny\b' => ['Suzuki', 'Jimny'], '\bswift\b' => ['Suzuki', 'Swift'], '\bvitara\b' => ['Suzuki', 'Vitara'],
        '\becosport\b' => ['Ford', 'EcoSport'], '\bescape\b' => ['Ford', 'Escape'],
        '\beverest\b' => ['Ford', 'Everest'], '\bexplorer\b' => ['Ford', 'Explorer'],
        '\bfiesta\b' => ['Ford', 'Fiesta'], '\bfocus\b' => ['Ford', 'Focus'],
        '\blynx\b' => ['Ford', 'Lynx'], '\branger\b' => ['Ford', 'Ranger'],
        '\bforester\b' => ['Subaru', 'Forester'], '\bimpreza\b' => ['Subaru', 'Impreza'], '\blegacy\b' => ['Subaru', 'Legacy'],
        '\bcarens\b' => ['Kia', 'Carens'], '\bcarnival\b' => ['Kia', 'Carnival'], '\bforte\b' => ['Kia', 'Forte'],
        '\bpicanto\b' => ['Kia', 'Picanto'], '\brio\b' => ['Kia', 'Rio'], '\bsoluto\b' => ['Kia', 'Soluto'],
        '\bsorento\b' => ['Kia', 'Sorento'], '\bsoul\b' => ['Kia', 'Soul'], '\bsportage\b' => ['Kia', 'Sportage'],
        '\bdmax\b|\bd-max\b' => ['Isuzu', 'D-Max'], '\bmux\b|\bmu-x\b' => ['Isuzu', 'MU-X'],
        '\bmg ?5\b' => ['MG', '5'], '\bmg ?zs\b' => ['MG', 'ZS'],
        '\bsantana\b' => ['Volkswagen', 'Santana'], '\bvolkswagen\b' => ['Volkswagen', 'Universal'],
        '\bwrangler\b' => ['Jeep', 'Wrangler'], '\bfoton transvan' => ['Foton', 'Transvan'],
        '\bfoton traveller\b' => ['Foton', 'Traveller'],
        '\buniversal\b|\bnon-frame\b|^[0-9]+.*t6' => ['Universal', 'Head Unit'],
    ];

    public function handle(): int
    {
        $type = ProductType::query()->where('name', 'Head Unit Panel')->first();

        if (! $type) {
            $this->components->error('The Head Unit Panel product type was not found.');

            return self::FAILURE;
        }

        $products = $type->products()->orderBy('id')->get();

        if ($products->isEmpty()) {
            $this->components->warn('There are no Head Unit Panel products to consolidate.');

            return self::SUCCESS;
        }

        $backupPath = 'catalog-backups/head-unit-panels-'.now()->format('Ymd-His').'.json';
        Storage::disk('local')->put($backupPath, $products->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $groups = $products->groupBy(function (Product $product): string {
            [$brand, $model] = $this->vehicleFor($product->name);

            return $brand.'|'.$model;
        })->sortKeys();

        DB::transaction(function () use ($groups, $products, $type): void {
            $products->each->delete();

            $groups->values()->each(function (Collection $variants, int $index) use ($type): void {
                /** @var Product $firstVariant */
                $firstVariant = $variants->first();
                [$brand, $model] = $this->vehicleFor($firstVariant->name);
                $fitments = $variants->pluck('name')->unique(fn (string $name): string => Str::lower($name))->sort()->values();
                $images = $variants->flatMap(fn (Product $product): array => [
                    $product->image_path,
                    ...($product->gallery_images ?? []),
                ])->filter()->unique()->values();
                $productName = $brand === 'Universal' ? 'Universal Head Unit Panel' : $brand.' '.$model.' Panel';

                Product::query()->create([
                    'product_type_id' => $type->id,
                    'name' => $productName,
                    'slug' => $this->uniqueSlug($productName),
                    'description' => $this->description($brand, $model, $fitments),
                    'specification' => 'Includes: Head unit panel · GPS antenna · USB port · Power harness',
                    'price' => null,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                    'is_featured' => $images->isNotEmpty(),
                    'image_path' => $images->first(),
                    'gallery_images' => $images->slice(1)->take(8)->values()->all(),
                    'image_alt' => $images->isNotEmpty() ? $productName : null,
                    'is_sample_image' => false,
                    'image_source' => null,
                ]);
            });
        });

        $this->components->info("Consolidated {$products->count()} panel variants into {$groups->count()} vehicle-model products.");
        $this->components->info('Backup saved to storage/app/private/'.$backupPath.'.');

        return self::SUCCESS;
    }

    /** @return array{0: string, 1: string} */
    private function vehicleFor(string $name): array
    {
        $normalizedName = Str::lower(trim(preg_replace('/\s+/', ' ', $name) ?? $name));

        foreach (self::VEHICLE_PATTERNS as $pattern => $vehicle) {
            if (preg_match('/'.$pattern.'/i', $normalizedName) === 1) {
                return $vehicle;
            }
        }

        $model = preg_replace('/\s+(?:19|20)\d{2}.*$/', '', $name) ?: $name;
        $model = trim(preg_replace('/\s*\(ID\s+\d+\)\s*/i', '', $model) ?? $model);

        return ['Other', Str::headline($model)];
    }

    /** @param Collection<int, string> $fitments */
    private function description(string $brand, string $model, Collection $fitments): string
    {
        $vehicleName = $brand === 'Universal' ? 'universal head unit installations' : $brand.' '.$model;
        $fitmentList = $fitments->map(fn (string $fitment): string => '• '.$fitment)->implode("\n");

        return "A vehicle-specific dashboard panel package for compatible {$vehicleName} variants. Confirm the exact year, trim, panel size, and Canbus requirement with your Armour branch before installation.\n\nEvery panel package includes:\n• Head unit panel\n• GPS antenna\n• USB port\n• Power harness\n\nAvailable panel fitments:\n{$fitmentList}";
    }

    private function uniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $suffix = 2;

        while (Product::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
