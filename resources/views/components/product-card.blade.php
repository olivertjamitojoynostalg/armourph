@props(['product'])

<article class="product-card reveal">
    <a class="catalog-image" href="{{ route('products.show', $product) }}">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->image_alt ?: $product->name }}" loading="lazy" width="600" height="400">
        @else
            <span>Photo coming soon</span>
        @endif
        @if ($product->is_sample_image)
            <small>Sample image</small>
        @endif
    </a>
    <div class="product-info">
        <span>{{ $product->productType?->name }} @if ($product->badge) · {{ $product->badge }} @endif</span>
        <h3>{{ $product->name }}</h3>
        @if ($product->description)
            <p>{{ $product->description }}</p>
        @endif
        <div class="product-card-footer">
            <a href="{{ route('products.show', $product) }}">View details ↗</a>
        </div>
    </div>
</article>
