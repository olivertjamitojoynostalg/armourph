<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class CatalogSyncController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        try {
            $counts = $this->synchronizeFromSource();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['sync' => 'The AdvanceAutoPH sync could not be completed. Please try again.']);
        }

        return back()->with('status', $this->statusMessage($counts));
    }

    /**
     * @return array{products_updated: int, products_created: int, packages_updated: int, packages_created: int, branches: int}
     */
    public function synchronizeFromSource(): array
    {
        $baseUrl = rtrim((string) config('services.advance_auto.url'), '/');
        $token = (string) config('services.advance_auto.token');

        if ($baseUrl === '' || $token === '') {
            throw new RuntimeException('AdvanceAutoPH sync is not configured.');
        }

        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout(30)
            ->retry(2, 250)
            ->get($baseUrl.'/api/v1/website-catalog')
            ->throw();

        $payload = Validator::make($response->json(), $this->payloadRules())->validate();

        return $this->synchronize($payload);
    }

    /**
     * @param  array{products_updated: int, products_created: int, packages_updated: int, packages_created: int, branches: int}  $counts
     */
    public function statusMessage(array $counts): string
    {
        return sprintf(
            'Sync complete: %d products updated, %d products added, %d packages updated, %d packages added, and %d active branches imported.',
            $counts['products_updated'],
            $counts['products_created'],
            $counts['packages_updated'],
            $counts['packages_created'],
            $counts['branches'],
        );
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function payloadRules(): array
    {
        return [
            'version' => ['required', 'integer', 'in:1'],
            'products' => ['required', 'array'],
            'products.*.source_id' => ['required', 'integer', 'min:1', 'distinct'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.type' => ['nullable', 'string', 'max:255'],
            'products.*.price' => ['nullable', 'numeric', 'min:0'],
            'products.*.is_active' => ['required', 'boolean'],
            'packages' => ['required', 'array'],
            'packages.*.source_id' => ['required', 'integer', 'min:1', 'distinct'],
            'packages.*.name' => ['required', 'string', 'max:255'],
            'packages.*.price' => ['nullable', 'numeric', 'min:0'],
            'branches' => ['required', 'array'],
            'branches.*.source_id' => ['required', 'integer', 'min:1', 'distinct'],
            'branches.*.name' => ['required', 'string', 'max:100'],
            'branches.*.address' => ['nullable', 'string', 'max:255'],
            'branches.*.contact' => ['nullable', 'string', 'max:100'],
            'branches.*.is_active' => ['required', 'boolean'],
            'branches.*.is_warehouse' => ['required', 'boolean'],
        ];
    }

    /**
     * @param  array{products: array<int, array<string, mixed>>, packages: array<int, array<string, mixed>>, branches: array<int, array<string, mixed>>}  $payload
     * @return array{products_updated: int, products_created: int, packages_updated: int, packages_created: int, branches: int}
     */
    private function synchronize(array $payload): array
    {
        $counts = ['products_updated' => 0, 'products_created' => 0, 'packages_updated' => 0, 'packages_created' => 0, 'branches' => 0];

        DB::transaction(function () use ($payload, &$counts): void {
            $productTypes = ProductType::query()->get()->keyBy(fn (ProductType $type): string => Str::lower($type->name));
            $nextProductOrder = ((int) Product::query()->max('sort_order')) + 1;

            foreach ($payload['products'] as $sourceProduct) {
                $typeName = trim((string) ($sourceProduct['type'] ?? ''));
                $productTypeId = null;

                if ($typeName !== '') {
                    $typeKey = Str::lower($typeName);
                    $productType = $productTypes->get($typeKey) ?? ProductType::query()->create(['name' => $typeName]);
                    $productTypes->put($typeKey, $productType);
                    $productTypeId = $productType->id;
                }

                $product = Product::query()->where('source_id', $sourceProduct['source_id'])->first();

                if ($product) {
                    $updates = ['price' => $sourceProduct['price'], 'product_type_id' => $productTypeId];
                    if (! $sourceProduct['is_active']) {
                        $updates['is_published'] = false;
                    }
                    $product->update($updates);
                    $counts['products_updated']++;

                    continue;
                }

                Product::query()->create([
                    'source_id' => $sourceProduct['source_id'],
                    'product_type_id' => $productTypeId,
                    'name' => $sourceProduct['name'],
                    'slug' => $this->uniqueSlug(Product::class, $sourceProduct['name'], (int) $sourceProduct['source_id']),
                    'price' => $sourceProduct['price'],
                    'sort_order' => $nextProductOrder++,
                    'is_published' => false,
                    'is_featured' => false,
                ]);
                $counts['products_created']++;
            }

            $productSourceIds = collect($payload['products'])->pluck('source_id');
            Product::query()->whereNotNull('source_id')->whereNotIn('source_id', $productSourceIds)->update(['is_published' => false]);

            $nextPackageOrder = ((int) Package::query()->max('sort_order')) + 1;
            foreach ($payload['packages'] as $sourcePackage) {
                $package = Package::query()->where('source_id', $sourcePackage['source_id'])->first();

                if ($package) {
                    $package->update(['price' => $sourcePackage['price']]);
                    $counts['packages_updated']++;

                    continue;
                }

                Package::query()->create([
                    'source_id' => $sourcePackage['source_id'],
                    'name' => $sourcePackage['name'],
                    'slug' => $this->uniqueSlug(Package::class, $sourcePackage['name'], (int) $sourcePackage['source_id']),
                    'description' => '',
                    'price' => $sourcePackage['price'],
                    'sort_order' => $nextPackageOrder++,
                    'is_published' => false,
                    'is_featured' => false,
                ]);
                $counts['packages_created']++;
            }

            $packageSourceIds = collect($payload['packages'])->pluck('source_id');
            Package::query()->whereNotNull('source_id')->whereNotIn('source_id', $packageSourceIds)->update(['is_published' => false]);

            $nextBranchOrder = ((int) Branch::query()->max('sort_order')) + 1;
            $sourceBranches = collect($payload['branches'])->reject(fn (array $branch): bool => $branch['is_warehouse']);
            foreach ($sourceBranches as $sourceBranch) {
                $branch = Branch::query()->where('source_id', $sourceBranch['source_id'])->first();
                $values = [
                    'name' => $sourceBranch['name'],
                    'address' => $sourceBranch['address'] ?: 'Address to be confirmed',
                    'contact' => $sourceBranch['contact'],
                ];

                if ($branch) {
                    if (! $sourceBranch['is_active']) {
                        $values['is_published'] = false;
                    }
                    $branch->update($values);

                    continue;
                }

                Branch::query()->create([
                    ...$values,
                    'source_id' => $sourceBranch['source_id'],
                    'hours' => 'Contact branch for opening hours',
                    'sort_order' => $nextBranchOrder++,
                    'is_published' => $sourceBranch['is_active'],
                ]);
            }

            Branch::query()
                ->whereNotNull('source_id')
                ->whereNotIn('source_id', $sourceBranches->pluck('source_id'))
                ->update(['is_published' => false]);
            $counts['branches'] = $sourceBranches->where('is_active', true)->count();
        });

        return $counts;
    }

    /**
     * @param  class-string<Product|Package>  $modelClass
     */
    private function uniqueSlug(string $modelClass, string $name, int $sourceId): string
    {
        $baseSlug = Str::slug($name) ?: 'item-'.$sourceId;
        $slug = $baseSlug;
        $suffix = 2;

        while ($modelClass::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }
}
