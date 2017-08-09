<?php

namespace App\Providers;

use BtcAutoTrader\Api\BitX;
use BtcAutoTrader\Api\Client;
use BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryEloquent;
use BtcAutoTrader\Orders\OrderRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

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
            return (new BitX(
                new Client(new \GuzzleHttp\Client()),
                env('BITX_ZAR_ACCOUNT_ID'),
                env('BITX_XBT_ACCOUNT_ID')
            ))->setAuth(env('BITX_KEY'), env('BITX_SECRET'));
        });

        $this->app->bind('BtcAutoTrader\Orders\OrderRepositoryInterface', function() {
            return new OrderRepositoryEloquent();
        });

    }
}
