  <header class="site-header">
    <div class="container nav-wrap">
      <a class="brand" href="{{ route('home') }}" aria-label="Armour home">
        <img src="{{ asset('assets/armour-logo.png') }}" alt="Armour">
      </a>

      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
        <span></span><span></span><span></span>
        <span class="sr-only">Open menu</span>
      </button>

      <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a href="{{ route('packages.index') }}">Packages</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('about') }}" @if (request()->routeIs('about')) aria-current="page" @endif>About</a>
        <a href="{{ route('dealers') }}" @if (request()->routeIs('dealers')) aria-current="page" @endif>Authorized dealers</a>
        <a href="{{ route('home') }}#branches">Branches</a>
        <a href="{{ route('home') }}#stores">Online stores</a>
      </nav>

      <a class="button button-small header-cta" href="{{ route('home') }}#branches">Find a branch</a>
    </div>
  </header>
