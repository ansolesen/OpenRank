<?php



return [

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here, you can configure authorization and caching.
    | You can request an API-key at: https://openrank.io
    |
    */
   'cache' => [
        'repository' => env('OPEN_RANK_CACHE_REPOSITORY', 'Illuminate\Contracts\Cache\Repository'),
        'minutes' => env('OPEN_RANK_CACHE_MINUTES', 1450)
   ],
    'api_key' => env('OPEN_RANK_API_KEY', ''),




];
