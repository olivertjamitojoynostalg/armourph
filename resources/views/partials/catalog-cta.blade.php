<div class="detail-cta-group">
    <a class="button" href="{{ route('home') }}#branches">Find a branch near you ↗</a>
    @if ($detailStores !== [])
        <div class="detail-platform-links" aria-label="Connect and shop online">
            <span>Or connect online</span>
            <div>
                @foreach ($detailStores as $store)
                    @php($platformKey = strtolower($store['name']))
                    <a class="detail-platform-link-{{ $platformKey }}" href="{{ $store['url'] }}" target="_blank" rel="noopener" aria-label="Open Armour on {{ $store['name'] }}" title="{{ $store['name'] }}">
                        <img src="{{ asset('assets/platforms/'.$platformKey.'.svg') }}" alt="" aria-hidden="true">
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
