    <section class="section packages" id="packages">
      <div class="container">
        <div class="section-heading reveal">
          <div>
            <p class="eyebrow"><span></span> Best-selling upgrades</p>
            <h2>Choose your <em>Armour package.</em></h2>
          </div>
          <div class="section-heading-actions"><p>Complete upgrade combinations made simpler. Compare inclusions, find the right setup, then visit your nearest branch.</p><a class="text-link" href="{{ route('packages.index') }}">See all packages <span>↗</span></a></div>
        </div>

        <p class="mobile-carousel-hint">Swipe to browse packages <span aria-hidden="true">→</span></p>
        <div class="package-grid" role="region" aria-label="Featured packages" tabindex="0">
          @foreach ($packages as $package)
            <x-package-card :package="$package" />
          @endforeach
        </div>
        <p class="data-note">Confirm vehicle compatibility and final installation pricing with your branch. Sample images are for illustration.</p>
      </div>
    </section>
