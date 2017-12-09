<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRate;
use BtcAutoTrader\ExchangeRates\ExchangeRateReporter;
use BtcAutoTrader\ExchangeRates\ExchangeRateUpdater;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ExchangeRatesController
{
    public function index(Request $request, ExchangeRateReporter $exchangeRateReporter)
    {
        if (!$request->get('from_iso') || !$request->get('to_iso')) {
            dd('Need from_iso and to_iso'); //TODO, use proper error handling dumbass
        }

        //If the 'from_date' is passed we add a second to it to make sure not to get the last record (maybe the client should be responsible for this, but meh
        $from_date = $request->get('from_date')
            ? strtotime($request->get('from_date')) + 1
            : strtotime('-12 hours');

        return $exchangeRateReporter->getExchangeRate(
            $request->get('from_iso'),
            $request->get('to_iso'),
            $from_date,
            time()
        );
    }

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
                Log::warning(__METHOD__.'(): Errors found: '.$exchangeRateUpdater->getErrors());
                return response($exchangeRateUpdater->getErrors(), 400);
            }

            return response($exchangeRate);
        }catch (\Exception $e) {
            Log::warning(__METHOD__.'(): Exception: '.$e->getMessage());
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
            Log::warning(__METHOD__.'(): Exception: '.$e->getMessage());
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