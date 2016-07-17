<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $client = new Stevenmaguire\Yelp\Client(array(
        'consumerKey' => config('services.yelp.key'), 
        'consumerSecret' => config('services.yelp.secret'),
        'token' => config('services.yelp.token'),
        'tokenSecret' => config('services.yelp.tokenSecret'),
        'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
    ));

    $results = $client->search(['term' => 'Hamburger', 'location' => 'Chicago, IL']);

    dd($results);

    // Shape of the results:
    //
    // region
    //  span
    //      latitude_delta
    //      longitude_delta
    //  center
    //      latitude
    //      longitude
    //  total: integer (?)
    //  businesses: [
    //      {
    //          is_claimed: true
    //          rating: 4.5
    //          mobile_url: ""
    //          rating_img_url: ""
    //          review_count: 123
    //          name: "Bubbas"
    //          rating_img_url_small: ""
    //          url: ""
    //          categories: [
    //              [
    //                  "Hot dogs"
    //                  "hotdog"
    //              ],
    //              [
    //                  "Burgers"
    //                  "burgers"
    //              ]
    //          ]
    //          menu_date_updated: unixdate
    //          phone: "7731234567"
    //          snippet_text: ""
    //          image_url: ""
    //          snippet_image_url: ""
    //          display_phone: "+1-773-123-4567"
    //          rating_img_url_large: ""
    //          menu_provider: ""
    //          id: "bubbas-burgers"
    //          is_closed: fales
    //          location: {
    //              cross_streets: "Oakley Blvd & Leavitt St"
    //              city: "Chicago"
    //              display_address: [
    //                  "2258 W Chicago Ave"
    //                  "Ukrainian Village"
    //                  "Chicago, IL 60622"
    //              ]
    //              geo_accuracy: 9.5
    //              neighborhoods: [
    //                  "Ukrainian Village"
    //                  "West Town"
    //              ]
    //              postal_code: "60622"
    //              country_code: "US"
    //              address: {
    //                  "2258 W Chicago Ave"
    //              }
    //              coordinate: {
    //                  latitude: 41.8608012017401724014
    //                  longitude: -87.140182308
    //              }
    //              state_code: "IL"
    //          }
    //      }
    //  ]
});
