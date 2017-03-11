<?php

namespace BtcAutoTrader\ExchangeRates;

class ExchangeRateUpdater
{
    protected $exchangeRateFetcher;
    protected $exchangeRateRepository;

    public function __construct(
        ExchangeRateFetcher $exchangeRateFetcher,
        ExchangeRateRepositoryInterface $exchangeRateRepository
    ) {
    
        $this->exchangeRateFetcher = $exchangeRateFetcher;
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    /**
     * Update a currency pair's exchange rate
     * @todo: Add error handler
     *
     * @param $from_iso
     * @param $to_iso
     * @return bool
     */
    public function update($from_iso, $to_iso)
    {
        if (!$exchangeRate = $this->exchangeRateRepository->find($from_iso, $to_iso)) {
            return false;
        }

        if (!$rate = $this->exchangeRateFetcher->getRate($from_iso, $to_iso)) {
            return false;
        }

        $exchangeRate->setRate($rate);

        return $this->exchangeRateRepository->update($exchangeRate);
    }
}
