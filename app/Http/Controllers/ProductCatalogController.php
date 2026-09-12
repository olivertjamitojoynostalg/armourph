<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $sort = in_array($request->string('sort')->toString(), ['name', 'price_asc', 'price_desc', 'newest'], true)
            ? $request->string('sort')->toString()
            : 'recommended';
        $selectedType = ProductType::find($request->integer('type'));

        $products = Product::published()
            ->with('productType')
            ->when($selectedType, fn (Builder $query) => $query->whereBelongsTo($selectedType))
            ->when($search !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('specification', 'like', "%{$search}%");
            }))
            ->when($sort === 'recommended', fn (Builder $query) => $query->reorder()
                ->orderByRaw("CASE WHEN image_path IS NOT NULL AND image_path != '' THEN 0 ELSE 1 END")
                ->orderBy('sort_order')
                ->orderBy('id'))
            ->when($sort === 'name', fn (Builder $query) => $query->reorder()->orderBy('name'))
            ->when($sort === 'price_asc', fn (Builder $query) => $query->reorder()->orderByRaw('price IS NULL')->orderBy('price'))
            ->when($sort === 'price_desc', fn (Builder $query) => $query->reorder()->orderByRaw('price IS NULL')->orderByDesc('price'))
            ->when($sort === 'newest', fn (Builder $query) => $query->reorder()->orderByDesc('id'))
            ->paginate(18)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'productTypes' => ProductType::query()
                ->whereHas('products', fn ($query) => $query->where('is_published', true))
                ->withCount(['products' => fn ($query) => $query->where('is_published', true)])
                ->orderBy('name')
                ->get(),
            'selectedType' => $selectedType,
            'publishedProductCount' => Product::query()->where('is_published', true)->count(),
            'search' => $search,
            'sort' => $sort,
        ]);
    }
}
