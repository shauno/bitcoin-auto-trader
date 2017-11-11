<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRateReporter;

class ExchangeRateGapController
{
    public function index(ExchangeRateReporter $exchangeRateReporter)
    {
        return $exchangeRateReporter->getExchangeRateGap(strtotime('-12 hours'), time());
    }

    public function usdZarRollingAverage(ExchangeRateReporter $exchangeRateReporter)
    {
         $list = $exchangeRateReporter->getExchangeRateGap(strtotime('-2 hours'), time());

         $sum = 0;
         foreach ($list as $rate) {
            $sum += $rate['percent'];
         }

         return [
             'from_date' => min(array_keys($list)),
             'to_date' => max(array_keys($list)),
             'points' => count($list),
             'percent' => $sum / count($list),
         ];
    }

}