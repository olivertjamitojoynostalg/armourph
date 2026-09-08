    <section class="section packages" id="packages">
      <div class="container">
        <div class="section-heading reveal">
          <div>
            <p class="eyebrow"><span></span> Best-value upgrades</p>
            <h2>Choose your <em>Armour package.</em></h2>
          </div>
          <p>Complete upgrade combinations made simpler. Compare inclusions, find the right setup, then visit your nearest branch.</p>
        </div>

        <div class="package-grid">
          @foreach ($packages as $package)
            <x-package-card :package="$package" />
          @endforeach
        </div>
        <p class="data-note">Sample package names and prices are based on the provided internal catalog and can be replaced later.</p>
      </div>
    </section>
