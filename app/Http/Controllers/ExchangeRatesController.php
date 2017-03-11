<?php

namespace App\Http\Controllers;

use BtcAutoTrader\ExchangeRates\ExchangeRateUpdater;

class ExchangeRatesController
{
	/**
	 * Retrieve the latest exchange rates for the currency pair and update it
	 *
	 * @param string $from_iso
	 * @param string $to_iso
	 * @param ExchangeRateUpdater $exchangeRateUpdater
	 */
	public function update(string $from_iso, string $to_iso, ExchangeRateUpdater $exchangeRateUpdater)
	{
		if($exchangeRate = $exchangeRateUpdater->update($from_iso, $to_iso)) {
			return $exchangeRate;
		}

		return 500; //TODO, actual 500 error
	}
}