<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Support\Collection;

interface ExchangeRateRepositoryInterface
{
    /**
     * @return Collection
     */
    public function findAll() :Collection;

    /**
     * @param string $from_iso
     * @param string $to_iso
     * @return ExchangeRate|null
     */
    public function find(string $from_iso, string $to_iso) : ?ExchangeRate;

    /**
     * @param ExchangeRate $exchangeRate
     * @return ExchangeRate
     */
    public function update(ExchangeRate $exchangeRate) : ExchangeRate;

    /**
     * @param ExchangeRate $exchangeRate
     * @return mixed
     */
    public function log(ExchangeRate $exchangeRate) : ExchangeRateLog;
}
