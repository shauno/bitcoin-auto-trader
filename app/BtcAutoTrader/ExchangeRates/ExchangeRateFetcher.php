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
     *
     * @param ExchangeRate $exchangeRate
     * @return float
     */
    public function getRate(ExchangeRate $exchangeRate)
    {
        try {
            $response = $this->client->get($exchangeRate->getTrackerUrl());

            $content = json_decode($response->getBody()->getContents());

            foreach ($exchangeRate->getValueKey() as $key) {
                if (preg_match('/\[([0-9]+)\]/', $key, $match)) { //looking for "[n]" syntax as an array
                    if (isset($content[$match[1]])) {
                        $content = $content[$match[1]];
                    } else {
                        throw new \RuntimeException('Failed to parse exchange rate response from tracker');
                    }
                } else {
                    if (isset($content->$key)) {
                        $content = $content->$key;
                    } else {
                        throw new \RuntimeException('Failed to parse exchange rate response from tracker');
                    }
                }
            }

            return (float)$content;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to connect to exchange rate tracker');
        }
    }
}
