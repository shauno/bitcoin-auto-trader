<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRateReporter;
use Illuminate\Http\Request;

class ExchangeRateGapController
{
    public function index(Request $request, ExchangeRateReporter $exchangeRateReporter)
    {
        $from_date = $request->get('from_date')
            ? strtotime($request->get('from_date')) + 60
            : strtotime('-12 hours');

        return $exchangeRateReporter->getExchangeRateGap($from_date, time());
    }

    public function usdZarRollingAverage(ExchangeRateReporter $exchangeRateReporter)
    {
         $list = $exchangeRateReporter->getExchangeRateGap(strtotime('-6 hours'), time());

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