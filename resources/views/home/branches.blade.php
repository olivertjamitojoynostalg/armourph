    <section class="section branches" id="branches">
      <div class="container">
        <div class="section-heading reveal"><div><p class="eyebrow"><span></span> Visit Armour</p><h2>Find your nearest <em>branch.</em></h2></div><p>Replace the sample branches and map links below with the official Armour locations.</p></div>
        <div class="branch-layout">
          <div class="map-placeholder reveal"><span>Google Maps embed area</span><strong>Show all Armour branches here</strong><small>Replace with an iframe or your preferred maps integration</small></div>
          <div class="branch-list">
            @foreach ($branches as $branch)
            <x-branch-card :branch="$branch" />
          @endforeach
          </div>
        </div>
      </div>
    </section>
