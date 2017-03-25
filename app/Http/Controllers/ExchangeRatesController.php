<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRate;
use BtcAutoTrader\ExchangeRates\ExchangeRateReporter;
use BtcAutoTrader\ExchangeRates\ExchangeRateUpdater;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

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
            $exchangeRate = $exchangeRateUpdater->update($from_iso, $to_iso);

            if ($exchangeRateUpdater->hasErrors()) {
                return response($exchangeRateUpdater->getErrors(), 400);
            }

            return response($exchangeRate);
        }catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * Convenience method to update all rates in the system
     *
     * @param ExchangeRateUpdater $exchangeRateUpdater
     * @return Response|Collection
     */
    public function bulkUpdate(ExchangeRateUpdater $exchangeRateUpdater)
    {
        try {
            $exchangeRates = $exchangeRateUpdater->bulkUpdate();

            return $exchangeRates;
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    public function show(string $from_iso, string $to_iso, ExchangeRateReporter $exchangeRateReporter)
    {
        try {
            $list = $exchangeRateReporter->getExchangeRate($from_iso, $to_iso, strtotime('-14 days'), time());

            return $list;
        } catch (\Exception $e) {

        }
    }
}