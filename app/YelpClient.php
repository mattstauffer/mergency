<?php

namespace App;

use Stevenmaguire\Yelp\Client;

class YelpClient extends Client
{
    public function searchByLl($latlon, $attributes)
    {
        $attributes = array_merge([
            'll' => $latlon
        ], $attributes);

        return $this->request(
            $this->searchPath . "?" . $this->prepareQueryParams($attributes)
        );
    }
}
