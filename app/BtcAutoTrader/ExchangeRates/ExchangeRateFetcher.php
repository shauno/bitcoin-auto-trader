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
     * @param ExchangeRate $exchangeRate
     * @return float
     */
    public function getRate(ExchangeRate $exchangeRate)
    {
        $response = $this->client->get($exchangeRate->tracker_url);

        $content = json_decode($response->getBody()->getContents());

        foreach ($exchangeRate->getValueKey() as $key) {
            if (preg_match('/\[([0-9]+)\]/', $key, $match)) { //looking for "[n]" syntax as an array
                $content = $content[$match[1]];
            } else {
                $content = $content->$key;
            }
        }

        return (float)$content;
    }
}
