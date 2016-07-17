<!DOCTYPE html>
<html>
    <head>
        <title>Burgermergency</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }

            .direction {
                font-size: 56px;
            }

            .location-box {
                margin-top: 4em;
            }

            .location-box input, .location-box button {
                font-size: 38px;
                margin-left: 1em;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">It's a burgermergency!</div>
                @php
                    $first = current($results->businesses)
                @endphp
                <div class="direction">Go here now: <a href="{{ $first->url }}">{{ $first->name }}</a></div>
                <div class="location-box"><form action="/search" method="POST">{{ csrf_field() }}<input type="text" name="location" value="{{ $search }}"><button>Update Location</button></form></div>

                <br>
                <iframe
                  width="600"
                  height="450"
                  frameborder="0" style="border:0"
                  src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google.maps') }}&q=burgers
                  &center={{ $results->region->center->latitude }},{{ $results->region->center->longitude }}" allowfullscreen>
                </iframe>
            </div>
        </div>
    </body>
</html>
