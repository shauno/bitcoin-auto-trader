<?php

use Illuminate\Database\Seeder;

class CreateDefaultExchangeRates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new \BtcAutoTrader\ExchangeRates\ExchangeRate())->fill([
            'from_iso'    => 'USD',
            'to_iso'      => 'ZAR',
            'rate'        => 13,
            'tracker_url' => 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22USDZAR%22)&env=store://datatables.org/alltableswithkeys&format=json',
            'value_key'   => 'query.results.rate.Rate'
        ])->save();

        (new \BtcAutoTrader\ExchangeRates\ExchangeRate())->fill([
            'from_iso'    => 'XBT',
            'to_iso'      => 'ZAR',
            'rate'        => 10000,
            'tracker_url' => 'https://api.mybitx.com/api/1/ticker?pair=XBTZAR',
            'value_key'   => 'ask'
        ])->save();

        (new \BtcAutoTrader\ExchangeRates\ExchangeRate())->fill([
            'from_iso'    => 'XBT',
            'to_iso'      => 'USD',
            'rate'        => 1000,
            'tracker_url' => 'https://api.coinmarketcap.com/v1/ticker/bitcoin/',
            'value_key'   => '[0].price_usd'
        ])->save();

    }
}
