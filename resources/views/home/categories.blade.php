<section class="product-explorer" id="explore-products" aria-labelledby="product-explorer-title">
    <div class="container">
        <div class="product-explorer-heading">
            <div>
                <p>Shop by category</p>
                <h2 id="product-explorer-title">Explore our products</h2>
            </div>
            <a href="{{ route('products.index') }}">View all products <span aria-hidden="true">↗</span></a>
        </div>

        <div class="product-explorer-carousel">
            <button class="product-explorer-arrow product-explorer-prev" type="button" data-category-scroll="-1" aria-label="Previous product categories">‹</button>
            <div class="product-explorer-track" data-category-track>
                @foreach ($productTypes as $productType)
                    <a class="product-explorer-card" href="{{ route('products.index', ['type' => $productType]) }}">
                        <div class="product-explorer-image">
                            @if ($productType->image_url)
                                <img src="{{ $productType->image_url }}" alt="{{ $productType->image_alt ?: $productType->name }}" loading="lazy" width="360" height="260">
                            @else
                                <span>{{ Str::upper(Str::substr($productType->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <strong>{{ $productType->name }}</strong>
                        <small>{{ $productType->products_count }} {{ Str::plural('product', $productType->products_count) }}</small>
                    </a>
                @endforeach
            </div>
            <button class="product-explorer-arrow product-explorer-next" type="button" data-category-scroll="1" aria-label="Next product categories">›</button>
        </div>
    </div>
</section>
