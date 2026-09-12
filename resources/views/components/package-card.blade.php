@props(['package'])

<article class="package-card{{ $package->sort_order === 1 ? ' featured' : '' }} reveal">
    <div class="card-top">
        <span class="badge">{{ $package->badge ?: 'Package' }}</span>
        <span>{{ str_pad($package->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
    </div>
    <a class="catalog-image" href="{{ route('packages.show', $package) }}">
        @if ($package->image_url)
            <img src="{{ $package->image_url }}" alt="{{ $package->image_alt ?: $package->name }}" loading="lazy" width="600" height="400">
        @else
            <span>Photo coming soon</span>
        @endif
        @if ($package->is_sample_image)
            <small>Sample image</small>
        @endif
    </a>
    <div class="package-body">
        <h3>{{ $package->name }}</h3>
        <p class="package-meta">{{ $package->specification }}</p>
        <ul>
            @foreach ($package->inclusions ?? [] as $inclusion)
                <li>{{ $inclusion }}</li>
            @endforeach
        </ul>
        <div class="price-row">
            <a href="{{ route('packages.show', $package) }}" aria-label="View {{ $package->name }}">View package ↗</a>
        </div>
    </div>
</article>
