<!DOCTYPE html>
<html>
    <head>
        <title>Burgermergency</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet" type="text/css">

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

        <style>
            /* apply a natural box layout model to all elements, but allowing components to change */
            html {
              box-sizing: border-box;
            }
            *, *:before, *:after {
              box-sizing: inherit;
            }

            html, body {
                background: #e5c202;
                height: 100%;
            }

            img {
                max-width: 100%;
            }

            body {
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
                margin: 0;
                padding: 0;
                text-align: center;
                width: 100%;
            }

            .content {
                margin: 0 auto;
                padding: 50px; 
            }

            .title {
                font-size: 8.5vw;
            }

            @media only screen and (min-width: 1200px) {
                .title {
                    font-size: 96px;
                }
            }

            .direction {
                font-size: 56px;
            }

            .location-box {
                font-size: 50px;
                margin-bottom: 1em;
                margin-top: 0.5em;
            }

            .location-box input, .location-box button {
                font-size: 38px;
                padding: 0.25em;
            }

            .location-box button, .js-find {
                background: #eee;
                border: 1px solid #999;
                font-size: 24px;
                margin-top: 0.5em;
                padding: 0.5em;
            }

                .js-find {
                    cursor: pointer;
                }

                .js-find-loader {
                    color: rgba(0, 0, 0, 0.6);
                    font-size: 20px;
                }

                .js-find-loader.error-message {
                    font-size: 16px;
                }

            .burger {
                max-height: 40vh;
                margin: 0 auto;
            }

            .hidden {
                display: none; 
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <img src="burger.png" alt="Burger" class="burger"><br><br>
                
                <div class="title">It's a burgermergency!</div>

                <div class="location-box">Where are you?
                    <form action="/search" method="POST">
                        {{ csrf_field() }}
                        <input type="text" name="location" value="{{ substr($search, 0, 7) === 'latlon:' ? '' : $search }}" autofocus id="locationBox">
                        <br>
                        <button>Find me a burger!</button>
                    </form>
                    <a class="js-find hidden" id="js-find">Get my browser location</a>
                    <span class="js-find-loader" id="js-find-loader">Location loading...</span>
                </div>

                @if ($search)
                    @php
                        $first = $shops->first();
                    @endphp
                    <div class="direction">Go here now: <a href="{{ $first->url }}">{{ $first->name }}</a></div>
                @else 
                    <div class="direction">Fill out a location above to get your closest burger!</div>
                @endif

                @if (substr($search, 0, 7) === 'latlon:')
                    <p style="font-size: 18px;">Current location provided by your browser.<br>{{ $search }}</p>
                @endif

                <br><br><br><br>

                <p>Or, let Google Maps suggest based on your browser's location:</p>
                <br>

                <iframe
                  width="600"
                  height="450"
                  frameborder="0" style="border:0"
                  src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps') }}&q=burgers
                  {{--&center={{ $results->region->center->latitude }},{{ $results->region->center->longitude }}--}}" allowfullscreen>
                </iframe>

                <br><br><br><br><br><br>

                <div class="credits">Logo by stolkramaker for the Noun Project | <a href="https://github.com/mattstauffer/burgermergency">Project on GitHub</a></div>
            </div>
        </div>

        <script>
            var $input = document.getElementById('locationBox');
            $input.focus();
            $input.select();
        </script>

        <script>
            var position = {
                lat: null,
                lon: null
            };

            function activateButton()
            {
                document.getElementById('js-find').classList.remove('hidden');
                hideLoader();
            }

            function hideLoader()
            {
                document.getElementById('js-find-loader').classList.add('hidden');
            }

            var $getLocationButton = document.getElementById("js-find");
            $getLocationButton.onclick = function() { clickButton(); return false; }

            function clickButton()
            {
                window.location = "/latlon:" + position.lat + "," + position.lon;                
            }

            if (navigator.geolocation) {
                console.log('Geolocation is supported!');

                window.onload = function () {
                    var geoOptions = {
                        maximumAge: 5 * 60 * 1000, // 5 minutes
                        timeout: 10 * 1000, // 10 seconds
                    };

                    var geoSuccess = function (geoPosition) {
                        console.log('Permission!');

                        position.lat = geoPosition.coords.latitude;
                        position.lon = geoPosition.coords.longitude;

                        console.log(position);

                        activateButton();
                    };

                    var geoError = function (error) {
                        console.log('Error occurred. Error code: ' + error.code);

                        var messages = {
                            0 : 'Unknown error getting your location.',
                            1 : 'Permission denied getting your location.',
                            2 : 'Position available from your browser.',
                            3 : 'Timed out getting your location.'
                        };

                        var $loader = document.getElementById('js-find-loader');

                        $loader.classList.add('error-message');
                        $loader.innerHTML = 'ERROR: ' + messages[error.code];
                        // error.code can be:
                        //   0: unknown error
                        //   1: permission denied
                        //   2: position unavailable (error response from location provider)
                        //   3: timed out
                    };

                    navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
                };
                
            } else {
                console.log('Geolocation is not supported for this Browser/OS version yet.');
            }
        </script>
    </body>
</html>
