<?php

class ExchangeRateFetcherTest extends TestCase
{
    public function test_get_value_from_json()
    {
        $method = new ReflectionMethod('\BtcAutoTrader\ExchangeRates\ExchangeRateFetcher', 'getValueFromJson');
        $method->setAccessible(true);

        $exchangeRateFetcher = $this->app->make('\BtcAutoTrader\ExchangeRates\ExchangeRateFetcher');

        $json = new \stdClass();
        $json->object = new \stdClass();
        $json->object->key = 'Object Value'; //test object
        $json->array = ['key' => 'Array Value']; //test array
        $json->list = [['key' => 'List Value 1'], ['key' => 'List Value 2']]; //test numeric indexed array

        $this->assertEquals('Object Value', $method->invokeArgs($exchangeRateFetcher, array($json, ['object', 'key'])));
        $this->assertEquals('Array Value', $method->invokeArgs($exchangeRateFetcher, array($json, ['array', 'key'])));
        $this->assertEquals('List Value 1', $method->invokeArgs($exchangeRateFetcher, array($json, ['list', '[0]', 'key'])));
        $this->assertEquals('List Value 2', $method->invokeArgs($exchangeRateFetcher, array($json, ['list', '[1]', 'key'])));
    }

    public function test_get_rate()
    {
        $bitXClientMock = \Mockery::mock('\BtcAutoTrader\Api\Client')
            ->shouldReceive('get')
            ->andReturnUsing(function() {
                $return = new \stdClass();
                $return->object = new stdClass();
                $return->object->key = 1.1;
                $return->array = ['key' => 2.2]; //test array
                $return->list = [['key' => 3.3], ['key' => 4.4]]; //test numeric indexed array

                return $return;
            })
            ->getMock();

        $exchangeRateFetcher = new \BtcAutoTrader\ExchangeRates\ExchangeRateFetcher($bitXClientMock);
        $exchangeRate = factory(\BtcAutoTrader\ExchangeRates\ExchangeRate::class)->make();
        $exchangeRate->value_key = 'list.[0].key';

        $this->assertEquals(3.3, $exchangeRateFetcher->getRate($exchangeRate));
    }
}