<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PackageCatalogController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $sort = in_array($request->string('sort')->toString(), ['name', 'price_asc', 'price_desc', 'newest'], true)
            ? $request->string('sort')->toString()
            : 'recommended';
        $categories = Package::query()
            ->where('is_published', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->selectRaw('COUNT(*) as packages_count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();
        $selectedCategory = $categories->firstWhere('category', $request->string('category')->toString())?->category;

        $packages = Package::published()
            ->when($selectedCategory, fn (Builder $query) => $query->where('category', $selectedCategory))
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
            ->paginate(15)
            ->withQueryString();

        return view('packages.index', [
            'packages' => $packages,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'publishedPackageCount' => Package::query()->where('is_published', true)->count(),
            'search' => $search,
            'sort' => $sort,
        ]);
    }
}
