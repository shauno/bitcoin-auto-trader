<?php
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ExchangeRateRepositoryEloquentTest extends TestCase
{
    public function test_update_needs_an_id()
    {
        $exchangeRateRepositoryEloquent = new \BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryEloquent();
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();
        unset($exchangeRate->id);

        $this->expectException('LogicException');
        $exchangeRateRepositoryEloquent->update($exchangeRate);
    }

    public function test_update_saves()
    {
        $exchangeRateRepositoryEloquent = new \BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryEloquent();
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();
        $exchangeRate = Mockery::mock($exchangeRate)
            ->shouldReceive('save')
            ->once()
            ->andReturn($exchangeRate)
            ->getMock();

        $this->assertEquals($exchangeRate, $exchangeRateRepositoryEloquent->update($exchangeRate));
    }
}