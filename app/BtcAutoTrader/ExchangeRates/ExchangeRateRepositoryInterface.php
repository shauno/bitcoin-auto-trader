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
     * Find rates that haven't been updated since their `frequency`
     *
     * @return Collection
     */
    public function findForUpdate() :Collection;

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

    /**
     * Return the calculated buy gap using the current USDZAR:GAP rate
     *
     * @param HasRateInterface $exchangeRate
     * @return float
     */
    public function getBuyGap(HasRateInterface $exchangeRate) : float;

    /**
     * Return the calculated sell gap using the current USDZAR:GAP rate
     *
     * @param HasRateInterface $exchangeRate
     * @return float
     */
    public function getSellGap(HasRateInterface $exchangeRate) : float;
}
