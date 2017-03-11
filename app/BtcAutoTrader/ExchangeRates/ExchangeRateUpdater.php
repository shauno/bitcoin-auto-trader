<?php

namespace BtcAutoTrader\ExchangeRates;

use BtcAutoTrader\Errors\ErrorMessageTrait;
use Illuminate\Support\MessageBag;

class ExchangeRateUpdater
{
    use ErrorMessageTrait;

    protected $exchangeRateFetcher;
    protected $exchangeRateRepository;

    /**
     * @param ExchangeRateFetcher $exchangeRateFetcher
     * @param ExchangeRateRepositoryInterface $exchangeRateRepository
     */
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
     * @return ExchangeRate
     * @throws \RuntimeException
     */
    public function update($from_iso, $to_iso) : ?ExchangeRate
    {
        if (!$exchangeRate = $this->exchangeRateRepository->find($from_iso, $to_iso)) {
            $this->setErrors(new MessageBag(['from_iso_to_iso' => 'invalid']));
            return null;
        }

        $rate = $this->exchangeRateFetcher->getRate($exchangeRate);

        $exchangeRate->setRate($rate);

        return $this->exchangeRateRepository->update($exchangeRate);
    }
}
