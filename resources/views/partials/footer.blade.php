  <footer class="site-footer">
    <div class="container footer-grid">
      <div><img src="{{ asset('assets/armour-logo.png') }}" alt="Armour"><p>Car technology and accessories designed to make every drive smarter, safer, and more enjoyable.</p></div>
      <div><h2>Explore</h2><a href="{{ route('packages.index') }}">Packages</a><a href="{{ route('products.index') }}">Products</a><a href="{{ route('about') }}">About</a><a href="{{ route('dealers') }}">Authorized dealers</a><a href="{{ route('home') }}#branches">Branches</a></div>
      <div><h2>Connect</h2>@foreach ($footerStores as $store)<a href="{{ $store['url'] }}" target="_blank" rel="noopener">{{ $store['name'] }} ↗</a>@endforeach<a href="{{ route('home') }}#branches">Contact a branch</a><a href="{{ route('home') }}#top">Back to top</a></div>
    </div>
    <div class="container footer-bottom"><span>© <span id="year"></span> Armour. All rights reserved.</span><span>armourphilippines.com</span></div>
  </footer>
