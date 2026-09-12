    @if ($heroSlides->isNotEmpty())
      <section class="hero-carousel" id="top" data-hero-carousel aria-roledescription="carousel" aria-label="Armour promotions">
        <div class="hero-slides" aria-live="off">
          @foreach ($heroSlides as $slide)
            <article class="hero-slide hero-slide-{{ $slide->content_position }} {{ $loop->first ? 'active' : '' }}" data-hero-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}" @if (! $loop->first) inert @endif>
              <picture>
                @if ($slide->mobile_image_url)<source media="(max-width: 760px)" srcset="{{ $slide->mobile_image_url }}">@endif
                @if ($slide->tablet_image_url)<source media="(max-width: 1024px)" srcset="{{ $slide->tablet_image_url }}">@endif
                <img src="{{ $slide->desktop_image_url }}" alt="{{ $slide->image_alt }}" @if ($loop->first) fetchpriority="high" loading="eager" @else loading="lazy" @endif>
              </picture>
              @if ($slide->show_content)
                <div class="hero-slide-shade" aria-hidden="true"></div>
                <div class="container hero-slide-content">
                  @if ($slide->eyebrow)<p class="eyebrow"><span></span>{{ $slide->eyebrow }}</p>@endif
                  <h1>{{ $slide->title }} @if ($slide->accent)<em>{{ $slide->accent }}</em>@endif</h1>
                  @if ($slide->description)<p>{{ $slide->description }}</p>@endif
                  @if ($slide->button_label && $slide->safe_button_url)<a class="button" href="{{ $slide->safe_button_url }}">{{ $slide->button_label }} <span aria-hidden="true">↗</span></a>@endif
                </div>
              @endif
            </article>
          @endforeach
        </div>

        @if ($heroSlides->count() > 1)
          <div class="hero-carousel-pagination" data-hero-progress role="group" aria-label="Choose a promotion">
            @foreach ($heroSlides as $slide)
              <button class="{{ $loop->first ? 'active' : '' }}" type="button" data-hero-dot="{{ $loop->index }}" aria-label="Show promotion {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"><span></span></button>
            @endforeach
          </div>
        @endif
        <a class="hero-carousel-scroll" href="#explore-products">Explore our products <span aria-hidden="true">↓</span></a>
      </section>
    @endif

    <section class="hero" id="hero-intro">
      <div class="hero-grid" aria-hidden="true"></div>
      <div class="container hero-layout">
        <div class="hero-copy reveal">
          <p class="eyebrow"><span></span> {{ $hero['eyebrow'] ?? '' }}</p>
          <h1>{{ $hero['title'] ?? '' }}<br><em>{{ $hero['accent'] ?? '' }}</em></h1>
          <p class="hero-lead">{{ $hero['description'] ?? '' }}</p>
          <div class="hero-actions">
            <a class="button" href="#packages">Explore packages <span aria-hidden="true">↗</span></a>
            <a class="button button-ghost" href="{{ route('products.index') }}">Browse products</a>
          </div>
          <div class="trust-row" aria-label="Service benefits">
            <span>Quality-tested</span>
            <span>Expert installation</span>
            <span>After-sales support</span>
          </div>
        </div>

        <div class="hero-visual reveal" aria-label="{{ $hero['image_alt'] ?? 'Car interior' }}">
          @if(!empty($hero['image_path']))<picture>
            @if(!empty($hero['mobile_image_path']))<source media="(max-width: 760px)" srcset="{{ asset($hero['mobile_image_path']) }}">@endif
            <img class="hero-photo" src="{{ asset($hero['image_path']) }}" alt="{{ $hero['image_alt'] ?? 'Car interior' }}" width="1200" height="1200" loading="lazy"></picture>@endif
          @if($hero['is_sample_image'] ?? false)<span class="hero-photo-note">Sample image · For inspiration</span>@endif
        </div>
      </div>
      <div class="hero-index" aria-hidden="true">01</div>
    </section>
