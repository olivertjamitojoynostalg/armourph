    <section class="section products" id="products">
      <div class="container">
        <div class="section-heading reveal">
          <div><p class="eyebrow"><span></span> Build your setup</p><h2>Featured <em>products.</em></h2></div>
          <a class="text-link" href="#stores">See where to buy <span>↗</span></a>
        </div>

        <div class="product-grid">
          @foreach ($products as $product)
            <x-product-card :product="$product" />
          @endforeach
        </div>
      </div>
    </section>
