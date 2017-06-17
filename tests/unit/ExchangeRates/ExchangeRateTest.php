<?php

use Tests\TestCase;

class ExchangeRateTest extends TestCase
{
    public function test_hidden_fields_are_not_shown()
    {
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();
        $this->assertEquals(
            '{"id":1,"from_iso":"USD","to_iso":"ZAR","rate":13,"updated_at":"2017-03-14 20:06:14"}',
            $exchangeRate->toJson()
        );
    }

    public function test_value_key_returns_array()
    {
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();
        $this->assertEquals(['some', 'value', 'key'], $exchangeRate->getValueKey());
    }

    public function test_sanity_check_cached_time()
    {
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();

        $exchangeRate->updated_at = date('Y-m-d H:i:s');
        $this->assertEquals(true, $exchangeRate->sanityCheck());

        $exchangeRate->updated_at = date('Y-m-d H:i:s', strtotime('-9 minutes'));
        $this->assertEquals(true, $exchangeRate->sanityCheck());

        $exchangeRate->updated_at = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        $this->assertEquals(false, $exchangeRate->sanityCheck());

        $exchangeRate->updated_at = date('Y-m-d H:i:s', strtotime('-20 minutes'));
        $this->assertEquals(false, $exchangeRate->sanityCheck());
    }

    public function test_sanity_check_positive_rate()
    {
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();
        $exchangeRate->updated_at = date('Y-m-d H:i:s');

        $this->assertEquals(true, $exchangeRate->sanityCheck());

        $exchangeRate->rate = '0.01';
        $this->assertEquals(true, $exchangeRate->sanityCheck());

        $exchangeRate->rate = '0.00';
        $this->assertEquals(false, $exchangeRate->sanityCheck());

        $exchangeRate->rate = '-1.00';
        $this->assertEquals(false, $exchangeRate->sanityCheck());

        $exchangeRate->rate = 'Abc';
        $this->assertEquals(false, $exchangeRate->sanityCheck());
    }
}