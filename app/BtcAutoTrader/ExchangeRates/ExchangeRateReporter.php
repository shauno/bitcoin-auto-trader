<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Support\Collection;

class ExchangeRateReporter
{
    protected $exchangeRateRepository;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    public function getExchangeRate(string $from_iso, string $to_iso, int $from_date, int $to_date) : ?Collection
    {
        $list = (new ExchangeRateLog())
            ->where('from_iso', $from_iso)
            ->where('to_iso', $to_iso)
            ->where('created_at', '>=', date('Y-m-d H:i:s', $from_date))
            ->where('created_at', '<=', date('Y-m-d H:i:s', $to_date))
            ->orderBy('created_at', 'asc')
            ->get();

        return $list;
    }
}