<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Support\Collection;

class ExchangeRateRepositoryEloquent implements ExchangeRateRepositoryInterface
{
    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return (new ExchangeRate())->get();
    }

    /**
     * @return Collection
     */
    public function findForUpdate() :Collection
    {
        return (new ExchangeRate())
            ->whereRaw('DATE_ADD(updated_at, INTERVAL frequency MINUTE) <= CURRENT_TIMESTAMP()')
            ->get();
    }

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

        $exchangeRate->touch(); //forces the updated_at timestamp to be set and persisted
        $exchangeRate->save();
        return $exchangeRate;
    }

    /**
     * @param ExchangeRate $exchangeRate
     * @return ExchangeRateLog
     */
    public function log(ExchangeRate $exchangeRate) : ExchangeRateLog
    {
        $log = (new ExchangeRateLog())->fill([
            'from_iso' => $exchangeRate->getFromIso(),
            'to_iso' => $exchangeRate->getToIso(),
            'rate' => $exchangeRate->getRate(),
        ]);

        $log->save();

        return $log;
    }

    /**
     * @inheritdoc
     */
    public function getBuyGap(HasRateInterface $exchangeRate) : float
    {
        return $exchangeRate->getRate() / 2;
    }

    /**
     * @inheritdoc
     */
    public function getSellGap(HasRateInterface $exchangeRate) : float
    {
        return $exchangeRate->getRate() * 1.5;
    }

}
