<?php

namespace BtcAutoTrader\ExchangeRates;

use GuzzleHttp\Client;

class ExchangeRateFetcher
{
    protected $client;

    /**
     * ExchangeRateFetcher constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the exchange rate of a currency pair via Yahoo API
     * @todo: Handle errors and unexpected responses
     *
     * @param string $from_iso
     * @param string $to_iso
     * @return float
     */
    public function getRate(string $from_iso, string $to_iso)
    {
        $isoPair = $from_iso.$to_iso; //TODO, consider sanitation?
        $response = $this->client->get('http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22'.$isoPair.'%22)&env=store://datatables.org/alltableswithkeys&format=json');

        $content = json_decode($response->getBody()->getContents());

        return (float)$content->query->results->rate->Rate;
    }
}
