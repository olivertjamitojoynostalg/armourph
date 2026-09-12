<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\HeroSlide;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'packages' => Package::published()
                ->where('is_featured', true)
                ->reorder()
                ->orderByRaw("CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 0 ELSE 1 END")
                ->inRandomOrder()
                ->limit(6)
                ->get(),
            'products' => Product::published()
                ->where('is_featured', true)
                ->with('productType')
                ->reorder()
                ->orderByRaw("CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 0 ELSE 1 END")
                ->inRandomOrder()
                ->limit(8)
                ->get(),
            'heroSlides' => HeroSlide::published()->get(),
            'productTypes' => ProductType::query()
                ->whereHas('products', fn ($query) => $query->where('is_published', true))
                ->withCount(['products' => fn ($query) => $query->where('is_published', true)])
                ->orderByDesc('products_count')
                ->orderBy('name')
                ->limit(12)
                ->get(),
            'hero' => SiteSetting::content('hero'), 'branches' => Branch::published()->get()->values()->map(fn (Branch $branch, int $index): array => [...$branch->toArray(), 'number' => str_pad($index + 1, 2, '0', STR_PAD_LEFT)])->all(), 'stores' => SiteSetting::content('stores'),
        ]);
    }

    public function showProduct(Product $product): View
    {
        abort_unless($product->is_published, 404);

        return view('catalog.product', ['product' => $product->load('productType')]);
    }

    public function showPackage(Package $package): View
    {
        abort_unless($package->is_published, 404);

        return view('catalog.show', ['item' => $package]);
    }
}
