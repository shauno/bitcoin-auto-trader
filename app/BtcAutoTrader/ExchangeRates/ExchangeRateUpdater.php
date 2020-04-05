<?php

namespace BtcAutoTrader\ExchangeRates;

use BtcAutoTrader\Errors\ErrorMessagesInterface;
use BtcAutoTrader\Errors\ErrorMessageTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

class ExchangeRateUpdater implements ErrorMessagesInterface
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
     * @return ExchangeRate|null
     * @throws \Exception
     */
    public function update($from_iso, $to_iso) : ?ExchangeRate
    {
        if (!$exchangeRate = $this->exchangeRateRepository->find($from_iso, $to_iso)) {
            $this->setErrors(new MessageBag(['from_iso_to_iso' => 'invalid']));
            return null;
        }

        $rate = $this->exchangeRateFetcher->getRate($exchangeRate);
        $exchangeRate->setRate($rate);
        $this->exchangeRateRepository->log($exchangeRate);
        return $this->exchangeRateRepository->update($exchangeRate);
    }

    /**
     * Update all the exchange rates we have stored
     * @return Collection
     */
    public function bulkUpdate() : Collection
    {
        $exchangeRates = $this->exchangeRateRepository->findForUpdate();

        $return = new Collection();
        foreach ($exchangeRates as $exchangeRate) {
            try {
                $return->push($this->update($exchangeRate->getFromIso(), $exchangeRate->getToIso()));
            } catch (\Exception $e) {
                //TODO consider reporting this in the collection somehow?
            }
        }

        return $return;
    }
}
