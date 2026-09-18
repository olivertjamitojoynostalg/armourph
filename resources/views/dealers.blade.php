@extends('layouts.app')

@section('title', 'Authorized Dealers | Armour Philippines')
@section('meta_description', 'Find an authorized Armour dealer for genuine products, professional advice, and installation support.')

@section('content')
<section class="simple-dealers">
  <div class="container">
    <div class="simple-page-heading reveal">
      <p class="eyebrow"><span></span> Our trusted network</p>
      <h1>Authorized <em>dealers.</em></h1>
    </div>

    @if ($dealers->isNotEmpty())
      <div class="simple-dealer-grid">
        @foreach ($dealers as $dealer)
          <article class="simple-dealer-card reveal">
            <div class="simple-dealer-image"><img src="{{ $dealer->image_url }}" alt="{{ $dealer->name }}"></div>
            <h2>{{ $dealer->name }}</h2>
          </article>
        @endforeach
      </div>
    @else
      <div class="dealer-empty reveal"><h2>Dealer directory coming soon.</h2><p>Our authorized dealer list is currently being updated.</p></div>
    @endif
  </div>
</section>
@endsection
