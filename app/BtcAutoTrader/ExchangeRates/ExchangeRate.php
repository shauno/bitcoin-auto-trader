<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	public function setRate(float $rate)
	{
		$this->rate = $rate;
	}
}