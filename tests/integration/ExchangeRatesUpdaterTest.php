<?php

class ExchangeRatesUpdaterTest extends TestCase
{
    public function test_update_gets_and_saves_rate()
    {
        $exchangeRateFetcherMock = Mockery::mock(\BtcAutoTrader\ExchangeRates\ExchangeRateFetcher::class)
            ->shouldReceive('getRate')
            ->andReturn(10.0)
            ->getMock();

        $exchangeRateRepositoryMock = Mockery::mock(\BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryInterface::class)
            ->shouldReceive('find')
            ->andReturn(factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make())
            ->shouldReceive('log')
            ->andReturnUsing(function(\BtcAutoTrader\ExchangeRates\ExchangeRate $exchangeRate) {
                $exchangeRateLog = new \BtcAutoTrader\ExchangeRates\ExchangeRateLog();
                $exchangeRateLog->fill([
                    'from_iso' => $exchangeRate->getFromIso(),
                    'to_iso' => $exchangeRate->getToIso(),
                    'rate' => $exchangeRate->getRate(),
                ]);

                return $exchangeRateLog;
            })
            ->shouldReceive('update')
            ->andReturnUsing(function(\BtcAutoTrader\ExchangeRates\ExchangeRate $exchangeRate) {
                return $exchangeRate;
            })
            ->getMock();

        $exchangeRateUpdater = new \BtcAutoTrader\ExchangeRates\ExchangeRateUpdater(
            $exchangeRateFetcherMock,
            $exchangeRateRepositoryMock
        );

        $tmp = $exchangeRateUpdater->update('USD', 'ZAR');


    }
}
