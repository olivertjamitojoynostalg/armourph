    <section class="section stores" id="stores">
      <div class="container store-layout reveal">
        <div><p class="eyebrow"><span></span> Shop on your platform</p><h2>Armour is closer<br><em>than you think.</em></h2><p>Browse official listings, ask questions, or follow the latest installations and product releases.</p></div>
        <div class="store-links">
          @foreach ($stores as $store)
          <a href="{{ $store['url'] }}" target="_blank" rel="noopener"><span>{{ $store['label'] }}</span><b>{{ $store['name'] }}</b><i>↗</i></a>
          @endforeach
        </div>
      </div>
    </section>
