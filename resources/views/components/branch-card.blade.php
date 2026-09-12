@props(['branch', 'selected' => false])

<article class="branch-card reveal {{ $selected ? 'selected' : '' }}">
    <span>{{ $branch['number'] ?? '' }}</span>
    <div>
        <h3>{{ $branch['name'] }}</h3>
        <p>{{ $branch['address'] }}</p>
        <div class="branch-meta">
            @if (! empty($branch['contact']))
                <small><span>Contact</span>{{ $branch['contact'] }}</small>
            @endif
            @if (! empty($branch['hours']))
                <small><span>Hours</span>{{ $branch['hours'] }}</small>
            @endif
        </div>
    </div>
    <div class="branch-actions">
        @if (! empty($branch['embed_url']))
            <button
                class="map-button"
                type="button"
                data-branch-map
                data-location="{{ $branch['name'] }}"
                data-embed-url="{{ $branch['embed_url'] }}"
            >Show on map</button>
        @endif
        @if (! empty($branch['url']))
            <a class="text-link" href="{{ $branch['url'] }}" target="_blank" rel="noopener">Get directions ↗</a>
        @endif
    </div>
</article>
