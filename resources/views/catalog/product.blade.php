@extends('layouts.app')

@section('title', $product->name.' | Armour')

@section('content')
    @php($productImages = $product->product_images)

    <section class="section detail-section">
        <div class="container">
            <a class="text-link" href="{{ route('products.index') }}">← Back to products</a>

            <div class="detail-layout">
                <div>
                    @if($productImages !== [])
                        <div class="product-gallery {{ count($productImages) > 1 ? 'has-thumbnails' : '' }}" data-product-gallery>
                            @if(count($productImages) > 1)
                                <div class="product-gallery-thumbnails" role="list" aria-label="Product images">
                                    @foreach($productImages as $index => $image)
                                        <button
                                            class="product-gallery-thumbnail {{ $loop->first ? 'is-active' : '' }}"
                                            type="button"
                                            role="listitem"
                                            data-gallery-thumbnail
                                            data-image-url="{{ $image['url'] }}"
                                            data-image-alt="{{ $image['alt'] }}"
                                            aria-label="Show image {{ $index + 1 }} of {{ count($productImages) }}"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                        >
                                            <img src="{{ $image['url'] }}" alt="" loading="lazy" width="80" height="80">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            <div class="catalog-image detail-image" data-image-zoom tabindex="0" aria-label="Zoom image of {{ $product->name }}">
                                <img data-gallery-main src="{{ $productImages[0]['url'] }}" alt="{{ $productImages[0]['alt'] }}" width="800" height="600">
                                <span class="image-zoom-lens" aria-hidden="true"></span>
                                <span class="image-zoom-hint" aria-hidden="true">⌕ Hover to zoom</span>
                            </div>
                        </div>
                    @else
                        <div class="catalog-image detail-image"><span>Photo coming soon</span></div>
                    @endif

                    @if($product->is_sample_image)
                        <p class="data-note">
                            Sample image for illustration.
                            @if($product->image_source)
                                <a href="{{ $product->image_source }}" target="_blank" rel="noopener">Image source ↗</a>
                            @endif
                        </p>
                    @endif
                </div>

                <div>
                    <p class="eyebrow">{{ $product->productType?->name }}</p>
                    <h1>{{ $product->name }}</h1>
                    @if($product->specification_items !== [])
                        <ul class="detail-spec" aria-label="Product specifications">
                            @foreach($product->specification_items as $specification)
                                <li>{{ $specification }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if($product->description)
                        <p class="detail-description">{{ $product->description }}</p>
                    @endif
                    <p class="data-note">Confirm vehicle compatibility, availability, and final installation pricing with your branch.</p>
                    @include('partials.catalog-cta')
                </div>
            </div>
        </div>
    </section>
@endsection
