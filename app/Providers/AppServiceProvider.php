<?php

namespace App\Providers;

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
    }
}
