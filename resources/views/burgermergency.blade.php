<!DOCTYPE html>
<html>
    <head>
        <title>{{ $shops->isEmpty() ? '' : $shops->first()->name . ' | ' }}Burgermergency</title>
        <!--
            If you're reading this, I'm sorry.

            I'm actually an accomplished frontend web developer... despite how it may seem.

            Accomplished enough that it's paining me to put this online looking like this.

            But I'm trying to keep this development rapid because I really shouldn't even be working on it.

            I mean. It's an app about hamburgers. My company is hiring and my wife is about to give birth.

            And I'm working on an app about hamburgers.

            So. Please excuse the mess. <3
        -->

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@stauffermatt">
        <meta name="twitter:title" content="It's a Burgermergency!!!">
        <meta name="twitter:description" content="If you have a burgermergency, you need to find the nearest burger--stat!.">
        <meta name="twitter:image" content="http://burgermergency.com/twitter-burger.png">
        <meta name="twitter:creator" content="@stauffermatt">

        <meta property="og:locale" content="en_US">
        <meta property="og:title" content="It's a Burgermergency!!!">
        <meta property="og:description" content="If you have a burgermergency, you need to find the nearest burger--stat!">
        <meta property="og:url" content="{{ Request::url() }}">
        <meta property="og:site_name" content="Matt Stauffer">
        <meta property="og:image" content="http://burgermergency.com/og-burger.png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/style.css">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <img src="burger.png" class="burger" alt="Burger">

                <h1 class="title">It's a burgermergency!</h1>

                @if ($search)
                    @include('partials.restaurant', ['restaurant' => $shops->first()])
                @else
                    <div class="restaurant">Fill out a location below to get your closest burger!</div>
                @endif

                <div class="location-box">
                    Where are you?

                    <form action="/search" method="POST">
                        {{ csrf_field() }}
                        <input
                            type="text"
                            name="location"
                            value="{{ substr($search, 0, 7) === 'latlon:' ? '' : $search }}"
                            {{ Request::path() == '' ? 'autofocus' : '' }}
                            id="locationBox"
                            placeholder="600 E Grand Ave, Chicago, IL"
                            style="width: 100%">
                        <button id="burgerMe">BURGER ME!</button>
                    </form>

                    <a class="js-find hidden" id="js-find">Get my browser location</a>
                    <span class="js-find-loader" id="js-find-loader">Location loading...</span>
                </div>

                {{--
                <p>Or, let Google Maps suggest based on your browser's location:</p>
                <br>
                <iframe
                  width="600"
                  height="450"
                  frameborder="0" style="border:0"
                  src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps') }}&q=burgers" allowfullscreen>
                </iframe>
                --}}

                <div class="credits">
                    <a href="https://github.com/mattstauffer/burgermergency">Project on GitHub</a>
                </div>
            </div>
        </div>

        <script>
            if (window.location.pathname == '/') {
                var $input = document.getElementById('locationBox');
                $input.focus();
                $input.select();
            }
        </script>

        <script>
            var position = {
                lat: null,
                lon: null
            };

            var $loader = document.getElementById('js-find-loader');
            var $locationButton = document.getElementById('js-find');
            var $burgerMeButton = document.getElementById('burgerMe');
            var $location = document.getElementById('locationBox');

            var toggleButton = function toggleButton(on) {
                if (on) {
                    $locationButton.classList.remove('hidden');
                } else {
                    $locationButton.classList.add('hidden');
                }
            };

            var toggleLoader = function toggleLoader(on) {
                if (on) {
                    $loader.classList.remove('hidden');
                } else {
                    $loader.classList.add('hidden');
                }
            };

            var activateButton = function activateButton() {
                toggleButton(true);
                toggleLoader(false);
            };

            var handleBurgerMeButton = function handleBurgerMeButton() {
                // Check for empty
                if ($location.value == '') {
                    alert('Gotta enter something into the box first, slugger.');
                    return false;
                }

                return true;
            };

            var handleButtonClick = function handleButtonClick() {
                $locationButton.innerHTML = "Loading...";

                getLocation(function () {
                    window.location = "/latlon:" + position.lat + "," + position.lon;
                });
            };

            var getLocation = function getLocation(callback) {
                var geoOptions = {
                    maximumAge: 5 * 60 * 1000, // 5 minutes
                    timeout: 10 * 1000, // 10 seconds
                };

                var geoSuccess = function (geoPosition) {
                    position.lat = geoPosition.coords.latitude;
                    position.lon = geoPosition.coords.longitude;

                    callback();
                };

                var geoError = function (error) {
                    console.log('Error occurred. Error code: ' + error.code);

                    var messages = {
                        0 : 'Unknown error getting your location.',
                        1 : 'Permission denied getting your location.',
                        2 : 'Position available from your browser.',
                        3 : 'Timed out getting your location.'
                    };

                    $loader.classList.add('error-message');
                    $loader.innerHTML = 'ERROR: ' + messages[error.code];

                    toggleLoader(true);
                    toggleButton(false);
                };

                navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
            };

            $locationButton.onclick = function () {
                handleButtonClick();
                return false;
            };

            $burgerMeButton.onclick = function () {
                return handleBurgerMeButton();
            };

            if (navigator.geolocation) {
                console.log('Geolocation is supported!');

                activateButton();
            } else {
                console.log('Geolocation is not supported for this Browser/OS version yet');

                $loader.classList.add('error-message');
                $loader.innerHTML = "Your browser doesn't support location services.";
            }
        </script>

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', '{{ config('services.google.analytics') }}', 'auto');
            ga('send', 'pageview');
        </script>
    </body>
</html>
