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
            'from_iso' => 'USD',
            'to_iso'   => 'ZAR',
            'rate'     => 13,
        ])->save();
    }
}
