<?php

$tld = parse_url(config('app.url'), PHP_URL_HOST);

function prepareTerm($term)
{
    return str_replace(['-', '_'], [' ', ' '], $term);
}

Route::group(['domain' => "{term}.{$tld}"], function () use ($tld) {
    Route::get('/', function ($term) {
        $term = prepareTerm($term);
        return view('mergency')
            ->with('term', $term)
            ->with('results', null)
            ->with('shops', collect([]))
            ->with('search', null);
    });

    Route::get('{search}', function ($term, $locationSearch, App\YelpClient $yelp) {
        $minutes = 30;
        $term = prepareTerm($term);

        try {
            $results = Cache::remember('yelp:' . $locationSearch . ':' . $term, $minutes, function () use ($yelp, $locationSearch, $term) {
                if (substr($locationSearch, 0, 7) == 'latlon:') {
                    return $yelp->searchByLl(substr($locationSearch, 7), ['term' => $term]);
                }

                return $yelp->search(['term' => $term, 'location' => $locationSearch]);
            });
        } catch (Exception $e) {
            // @todo: Log and improve these
            if (strpos($e->getMessage(), 'UNAVAILABLE_FOR_LOCATION') !== false) {
                return 'Sorry, this service does not work in your current location. <a href="/">Back</a>';
            }

            return 'Sorry, there has been an unexpected error. <a href="/">Back</a>';
        }

        // Only businesses that have not been permanently closed
        $shops = collect($results->businesses)->reject(function ($shop) {
            return $shop->is_closed;
        });

        return view('mergency')
            ->with('term', $term)
            ->with('results', $results)
            ->with('shops', $shops)
            ->with('search', $locationSearch);
    });

    Route::post('search', function (Illuminate\Http\Request $request) {
        return redirect('/' . $request->input('location'));
    });
});

Route::get('/', function () use ($tld) {
    return redirect('http://burger.' . $tld);
});
