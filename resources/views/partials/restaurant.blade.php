<div class="restaurant">
    Go here now:<br>

    <a href="{{ $restaurant->url }}">{{ $restaurant->name }}</a><br>

    <div class="restaurant__details">
        @include('partials.rating', ['restaurant' => $restaurant])
        Phone: <a href="tel:{{ $restaurant->phone }}">{{ $restaurant->display_phone }}</a><br><br>

        <strong>Getting there:</strong><br>

        @if (substr($search, 0, 7) === 'latlon:')
            <em>
            {{ round(vincentyGreatCircleDistance(
                    $results->region->center->latitude,
                    $results->region->center->longitude,
                    $restaurant->location->coordinate->latitude,
                    $restaurant->location->coordinate->longitude
                )) }}
                meters away<br>
            </em>
        @endif

        {{-- @todo: probably need to handle address line two --}}
        <a href="https://maps.google.com?daddr={{ urlencode($restaurant->location->address[0] . ' ' . $restaurant->location->city . ' ' . $restaurant->location->state_code) }}">
        @foreach ($restaurant->location->display_address as $line)
            {{ $line }}<br>
        @endforeach
        </a>

        <i>(by {{ $restaurant->location->cross_streets }})</i>
    </div>
</div>
