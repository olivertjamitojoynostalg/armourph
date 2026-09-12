<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Armour car accessories, multimedia packages, cameras, panels, and professional installation.">
  <title>@yield('title', 'Armour | Upgrade Your Drive')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

@include('partials.header')

  <main id="main">
    @yield('content')
  </main>

@include('partials.footer')
@include('partials.inquiry-assistant')

  <div class="toast" role="status" aria-live="polite"></div>
  <script src="{{ asset('script.js') }}?v={{ filemtime(public_path('script.js')) }}"></script>
</body>
</html>
