<?php

namespace BtcAutoTrader\ExchangeRates;

use Mockery\Exception\RuntimeException;

class ExchangeRateRepositoryEloquent implements ExchangeRateRepositoryInterface
{
    /**
     * @param string $from_iso
     * @param string $to_iso
     * @return ExchangeRate|null
     */
    public function find(string $from_iso, string $to_iso) : ?ExchangeRate
    {
        return (new ExchangeRate())
            ->where('from_iso', $from_iso)
            ->where('to_iso', $to_iso)
            ->first();
    }

    /**
     * @param ExchangeRate $exchangeRate
     * @return ExchangeRate
     * @throws \LogicException
     */
    public function update(ExchangeRate $exchangeRate) : ExchangeRate
    {
        if (!$exchangeRate->getId()) {
            throw new \LogicException('Call to update() with model without and id');
        }

        $exchangeRate->save();
        return $exchangeRate;
    }
}
