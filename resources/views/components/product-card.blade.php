@props(['product'])
<article class="product-card reveal"><div class="image-placeholder"><strong>Product image</strong></div><div class="product-info"><span>{{ $product['category'] }}</span><h3>{{ $product['name'] }}</h3><p>{{ $product['description'] }}</p><div><b>{{ $product['price'] }}</b><a href="#branches">View details ↗</a></div></div></article>
