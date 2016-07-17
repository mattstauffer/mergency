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
                (not splat in the middle of Chicago, IL? Tough beans, you'll have to wait until this app is better and gets your actual location)
            </div>
        </div>
    </body>
</html>
