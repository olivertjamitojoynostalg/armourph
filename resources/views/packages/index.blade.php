@extends('layouts.app')

@section('title', ($selectedCategory ?: 'All Packages').' | Armour')

@section('content')
<section class="section catalog-page">
    <div class="container">
        <a class="text-link catalog-back-link" href="{{ route('home') }}">← Back to home</a>
        <header class="catalog-page-heading">
            <div>
                <p class="eyebrow"><span></span> Armour packages</p>
                <h1>{{ $selectedCategory ?: 'All packages' }}</h1>
                <p>Compare complete upgrade packages and ask a branch to confirm compatibility, availability, and installation pricing.</p>
            </div>
        </header>

        <div class="catalog-shop-layout">
            <aside class="catalog-sidebar">
                <h2>Categories</h2>
                <nav aria-label="Package categories">
                    <a class="{{ $selectedCategory ? '' : 'active' }}" href="{{ route('packages.index', array_filter(['search' => $search, 'sort' => $sort === 'recommended' ? null : $sort])) }}">
                        <span>All packages</span><small>{{ $publishedPackageCount }}</small>
                    </a>
                    @foreach ($categories as $category)
                        <a class="{{ $selectedCategory === $category->category ? 'active' : '' }}" href="{{ route('packages.index', array_filter(['category' => $category->category, 'search' => $search, 'sort' => $sort === 'recommended' ? null : $sort])) }}">
                            <span>{{ $category->category }}</span><small>{{ $category->packages_count }}</small>
                        </a>
                    @endforeach
                </nav>
            </aside>

            <div class="catalog-results">
                <form class="catalog-toolbar" method="GET" action="{{ route('packages.index') }}">
                    <label class="catalog-search"><span>Search packages</span><input type="search" name="search" value="{{ $search }}" placeholder="Search packages…"></label>
                    <label class="catalog-mobile-category"><span>Category</span><select name="category"><option value="">All packages</option>@foreach ($categories as $category)<option value="{{ $category->category }}" @selected($selectedCategory === $category->category)>{{ $category->category }}</option>@endforeach</select></label>
                    <label><span>Sort by</span><select name="sort"><option value="recommended" @selected($sort === 'recommended')>Recommended</option><option value="name" @selected($sort === 'name')>Name: A–Z</option><option value="newest" @selected($sort === 'newest')>Newest</option></select></label>
                    @if ($selectedCategory)<input type="hidden" name="category" value="{{ $selectedCategory }}" class="catalog-desktop-filter">@endif
                    <button type="submit">Apply</button>
                </form>

                <div class="catalog-results-heading"><p>{{ $packages->total() }} {{ Str::plural('package', $packages->total()) }}</p>@if ($search !== '')<span>Results for “{{ $search }}”</span>@endif</div>

                @if ($packages->isNotEmpty())
                    <div class="package-grid catalog-package-grid">@foreach ($packages as $package)<x-package-card :package="$package" />@endforeach</div>
                @else
                    <div class="catalog-empty"><h2>No packages found</h2><p>Try another search or category.</p><a class="text-link" href="{{ route('packages.index') }}">Clear filters</a></div>
                @endif

                <x-catalog-pagination :paginator="$packages" label="Package pages" />
            </div>
        </div>
    </div>
</section>
@endsection
