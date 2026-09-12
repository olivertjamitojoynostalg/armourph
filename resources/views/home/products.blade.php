    <section class="section products" id="products">
      <div class="container">
        <div class="section-heading reveal">
          <div><p class="eyebrow"><span></span> Customer favorites</p><h2>Featured <em>products.</em></h2></div>
          <a class="text-link" href="{{ route('products.index') }}">See all products <span>↗</span></a>
        </div>

        <p class="mobile-carousel-hint">Swipe to browse products <span aria-hidden="true">→</span></p>
        <div class="product-grid" role="region" aria-label="Featured products" tabindex="0">
          @foreach ($products as $product)
            <x-product-card :product="$product" />
          @endforeach
        </div>
      </div>
    </section>
