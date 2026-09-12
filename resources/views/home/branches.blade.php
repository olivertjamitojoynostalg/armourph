@php
    $mappedBranch = collect($branches)->first(fn (array $branch): bool => ! empty($branch['embed_url']));
@endphp

    <section class="section branches" id="branches">
      <div class="container">
        <div class="section-heading reveal"><div><p class="eyebrow"><span></span> Visit Armour</p><h2>Find your nearest <em>branch.</em></h2></div><p>Choose the Armour location most convenient for your installation or product inquiry.</p></div>
        <div class="branch-layout">
          <div class="branch-map reveal">
            @if ($mappedBranch)
              <iframe id="branch-map" src="{{ $mappedBranch['embed_url'] }}" title="Map of {{ $mappedBranch['name'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @else
              <iframe id="branch-map" title="Armour branch map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen hidden></iframe>
            @endif
            <div class="map-placeholder" data-branch-map-empty @if ($mappedBranch) hidden @endif>
              <span>Armour locations</span>
              <strong>Map preview</strong>
              <small>Add a Google Maps embed URL to a branch in website maintenance to show its map here.</small>
            </div>
          </div>
          <div class="branch-list">
            @foreach ($branches as $branch)
            <x-branch-card :branch="$branch" :selected="$mappedBranch && $mappedBranch['id'] === $branch['id']" />
          @endforeach
          </div>
        </div>
      </div>
    </section>
