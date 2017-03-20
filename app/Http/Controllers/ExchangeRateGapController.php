<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRateReporter;

class ExchangeRateGapController
{
    public function index(ExchangeRateReporter $exchangeRateReporter)
    {
        return $exchangeRateReporter->getExchangeRateGap(strtotime('-1 day'), time());
    }
}