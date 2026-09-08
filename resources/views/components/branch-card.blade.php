@props(['branch'])
<article class="branch-card reveal"><span>{{ $branch['number'] }}</span><div><h3>{{ $branch['name'] }}</h3><p>{{ $branch['address'] }}</p><small>{{ $branch['hours'] }}</small></div><button class="map-button" type="button" data-location="{{ $branch['name'] }}">Get directions ↗</button></article>
