<?php

namespace Dresing\OpenRank;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;


class OpenRankServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/open-rank.php', 'open_rank'
        );
        $this->app->bind(OpenRank::class, function ($app) {
            return new OpenRank(new Client([
                'base_uri' => 'https://openpagerank.com/api/v1.0/'
            ]), config('open_rank.api_key'), app()->make(config('open_rank.cache.repository')), config('open_rank.cache.minutes'));
        });
    }
}
