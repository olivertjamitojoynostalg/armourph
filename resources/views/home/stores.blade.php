    <section class="section stores" id="stores">
      <div class="container store-layout reveal">
        <div><p class="eyebrow"><span></span> Shop on your platform</p><h2>Armour is closer<br><em>than you think.</em></h2><p>Browse official listings, ask questions, or follow the latest installations and product releases.</p></div>
        <div class="store-links">
          @php
            $platformLogos = [
              'shopee' => 'assets/platforms/shopee.svg',
              'lazada' => 'assets/platforms/lazada.svg',
              'tiktok' => 'assets/platforms/tiktok.svg',
              'facebook' => 'assets/platforms/facebook.svg',
            ];
          @endphp
          @foreach ($stores as $store)
            @php($platformKey = strtolower($store['name']))
            <a href="{{ $store['url'] }}" target="_blank" rel="noopener">
              @if ($logoPath = ($platformLogos[$platformKey] ?? null))
                <img class="store-logo store-logo-{{ $platformKey }}" src="{{ asset($logoPath) }}" alt="" aria-hidden="true">
              @endif
              <span>{{ $store['label'] }}</span>
              <b>{{ $store['name'] }}</b>
              <i>↗</i>
            </a>
          @endforeach
        </div>
      </div>
    </section>
