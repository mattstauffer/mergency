<div class="restaurant">
    Go here now:<br>

    <a href="{{ $restaurant->url }}">{{ $restaurant->name }}</a><br>

    <div class="restaurant__details">
        @include('partials.rating', ['restaurant' => $restaurant])
        Phone: <a href="tel:{{ $restaurant->phone }}">{{ $restaurant->display_phone }}</a><br><br>

        <strong>Getting there:</strong><br>

        {{-- @todo: probably need to handle address line two --}}
        <a href="https://maps.google.com?daddr={{ urlencode($restaurant->location->address[0] . ' ' . $restaurant->location->city . ' ' . $restaurant->location->state_code) }}">
        @foreach ($restaurant->location->display_address as $line)
            {{ $line }}<br>
        @endforeach
        </a>
        @if (isset($restaurant->location->cross_streets))
        <i>(by {{ $restaurant->location->cross_streets }})</i>
        @endif
    </div>
</div>
