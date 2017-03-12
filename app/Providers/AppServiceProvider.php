<?php

namespace App\Providers;

use BtcAutoTrader\Api\BitX;
use BtcAutoTrader\Api\Client;
use BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryInterface', function() {
            return new ExchangeRateRepositoryEloquent();
        });

        $this->app->bind('BtcAutoTrader\Api\BitX', function() {
            return (new BitX(new Client(new \GuzzleHttp\Client())))->setAuth(env('BITX_KEY'), env('BITX_SECRET'));
        });

    }
}
