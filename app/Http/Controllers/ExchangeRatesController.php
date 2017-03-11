<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRate;
use BtcAutoTrader\ExchangeRates\ExchangeRateUpdater;

class ExchangeRatesController
{
    /**
     * Retrieve the latest exchange rates for the currency pair and update it
     *
     * @param string $from_iso
     * @param string $to_iso
     * @param ExchangeRateUpdater $exchangeRateUpdater
     * @return ExchangeRate
     */
    public function update(string $from_iso, string $to_iso, ExchangeRateUpdater $exchangeRateUpdater)
    {
        try {
            if ($exchangeRate = $exchangeRateUpdater->update($from_iso, $to_iso)) {
                return response($exchangeRate);
            } else {
                return response($exchangeRateUpdater->getErrors(), 400);
            }
        }catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}