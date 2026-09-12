@extends('layouts.app')

@section('title', ($selectedType?->name ?? 'All Products').' | Armour')

@section('content')
<section class="section catalog-page">
    <div class="container">
        <a class="text-link catalog-back-link" href="{{ route('home') }}">← Back to home</a>
        <header class="catalog-page-heading">
            <div>
                <p class="eyebrow"><span></span> Armour catalog</p>
                <h1>{{ $selectedType?->name ?? 'All products' }}</h1>
                <p>Browse car accessories and upgrades, then ask a branch to confirm availability and vehicle compatibility.</p>
            </div>
        </header>

        <div class="catalog-shop-layout">
            <aside class="catalog-sidebar">
                <h2>Categories</h2>
                <nav aria-label="Product categories">
                    <a class="{{ $selectedType ? '' : 'active' }}" href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort === 'recommended' ? null : $sort])) }}">
                        <span>All products</span><small>{{ $publishedProductCount }}</small>
                    </a>
                    @foreach ($productTypes as $productType)
                        <a class="{{ $selectedType?->is($productType) ? 'active' : '' }}" href="{{ route('products.index', array_filter(['type' => $productType->id, 'search' => $search, 'sort' => $sort === 'recommended' ? null : $sort])) }}">
                            <span>{{ $productType->name }}</span><small>{{ $productType->products_count }}</small>
                        </a>
                    @endforeach
                </nav>
            </aside>

            <div class="catalog-results">
                <form class="catalog-toolbar" method="GET" action="{{ route('products.index') }}">
                    <label class="catalog-search">
                        <span>Search products</span>
                        <input type="search" name="search" value="{{ $search }}" placeholder="Search products…">
                    </label>
                    <label class="catalog-mobile-category">
                        <span>Category</span>
                        <select name="type">
                            <option value="">All products</option>
                            @foreach ($productTypes as $productType)
                                <option value="{{ $productType->id }}" @selected($selectedType?->is($productType))>{{ $productType->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        <span>Sort by</span>
                        <select name="sort">
                            <option value="recommended" @selected($sort === 'recommended')>Recommended</option>
                            <option value="name" @selected($sort === 'name')>Name: A–Z</option>
                            <option value="newest" @selected($sort === 'newest')>Newest</option>
                        </select>
                    </label>
                    @if ($selectedType)<input type="hidden" name="type" value="{{ $selectedType->id }}" class="catalog-desktop-filter">@endif
                    <button type="submit">Apply</button>
                </form>

                <div class="catalog-results-heading"><p>{{ $products->total() }} {{ Str::plural('product', $products->total()) }}</p>@if ($search !== '')<span>Results for “{{ $search }}”</span>@endif</div>

                @if ($products->isNotEmpty())
                    <div class="product-grid catalog-product-grid">@foreach ($products as $product)<x-product-card :product="$product" />@endforeach</div>
                @else
                    <div class="catalog-empty"><h2>No products found</h2><p>Try another search or category.</p><a class="text-link" href="{{ route('products.index') }}">Clear filters</a></div>
                @endif

                <x-catalog-pagination :paginator="$products" label="Product pages" />
            </div>
        </div>
    </div>
</section>
@endsection
