@props(['package'])
<article class="package-card{{ $package['featured'] ? ' featured' : '' }} reveal">
  <div class="card-top"><span class="badge">{{ $package['badge'] }}</span><span>{{ $package['number'] }}</span></div>
  <div class="image-placeholder"><strong>Package image</strong><span>{{ $package['image_label'] }}</span></div>
  <div class="package-body">
    <h3>{{ $package['name'] }}</h3>
    <p class="package-meta">{{ $package['specification'] }}</p>
    <ul>@foreach ($package['inclusions'] as $inclusion)<li>{{ $inclusion }}</li>@endforeach</ul>
    <div class="price-row"><div><small>Package price</small><b>{{ $package['price'] }}</b></div><a href="#branches" aria-label="{{ $package['link_label'] }}">View package ↗</a></div>
  </div>
</article>
