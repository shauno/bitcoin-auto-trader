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

    /**
     * @param string $from_iso
     * @param string $to_iso
     * @param int $from_date Timestamp
     * @param int $to_date Timestamp
     * @return Collection
     */
    public function getExchangeRate(string $from_iso, string $to_iso, int $from_date, int $to_date) : Collection
    {
        //Should be using the repo fool TODO
        $list = (new ExchangeRateLog())
            ->where('from_iso', $from_iso)
            ->where('to_iso', $to_iso)
            ->where('created_at', '>=', date('Y-m-d H:i:s', $from_date))
            ->where('created_at', '<=', date('Y-m-d H:i:s', $to_date))
            ->orderBy('created_at', 'asc')
            ->get();

        return $list;
    }

    /**
     * Return the gap between XBT/ZAR and XBT/USD converted to ZAR, grouped by hour
     * @param int $from_date
     * @param int $to_date
     * @return array
     */
    public function getExchangeRateGap(int $from_date, int $to_date) : ?array
    {
        //Why not use the repo clown? TODO
        $list = (new ExchangeRateLog())
            ->where('created_at', '>=', date('Y-m-d H:i:s', $from_date))
            ->where('created_at', '<=', date('Y-m-d H:i:s', $to_date))
            ->whereIn('to_iso', ['USD', 'ZAR', 'GAP'])
            ->orderBy('created_at', 'asc')
            ->get();

        $group = [];

        foreach ($list as $log) {
            if($log->from_iso == 'USD' && $log->to_iso == 'ZAR') {
                $key = 'usd_zar';
            } elseif($log->from_iso == 'XBT' && $log->to_iso == 'USD') {
                $key = 'xbt_usd';
            } elseif($log->from_iso == 'XBT' && $log->to_iso == 'ZAR') {
                $key = 'xbt_zar';
            } elseif($log->from_iso == 'USDZAR' && $log->to_iso == 'GAP') {
                $key = 'usd_zar_rolling_gap';
                $exchangeRate = $this->exchangeRateRepository->find($log->from_iso, $log->to_iso);
                $group[date('Y-m-d H:i', $log->created_at->timestamp)]['usd_zar_rolling_buy_gap'] = $this->exchangeRateRepository->getBuyGap($exchangeRate);
                $group[date('Y-m-d H:i', $log->created_at->timestamp)]['usd_zar_rolling_sell_gap'] = $this->exchangeRateRepository->getSellGap($exchangeRate);
            }

            $group[date('Y-m-d H:i', $log->created_at->timestamp)][$key] = $log->rate;
        }

        $rates = [];
        foreach ($group as $date => $list) {
            if (isset($list['usd_zar'], $list['xbt_usd'])) {
                $xbtUsdInZar = $list['usd_zar'] * $list['xbt_usd']; //calc the equivalent zar price of a usd coin
                $rates[$date] = $list + [
                    'xbt_usd_in_zar' => $xbtUsdInZar,
                    'percent' => ($list['xbt_zar'] - $xbtUsdInZar) / $list['xbt_zar'] * 100,
                ];
            }
        }

        return $rates;
    }

}