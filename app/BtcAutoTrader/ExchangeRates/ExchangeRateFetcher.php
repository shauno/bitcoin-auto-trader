<?php

namespace BtcAutoTrader\ExchangeRates;

use BtcAutoTrader\Api\Client;

class ExchangeRateFetcher
{
    protected $client;

    protected $trackerCache = [];

    /**
     * ExchangeRateFetcher constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @return \stdClass|array
     */
    protected function getTrackerResponse($url)
    {
        $response = $this->trackerCache[$url] ?? false;
        if (!$response) {
            $response = $this->client->get($url);
            $this->trackerCache[$url] = $response;
        }

        return $response;
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
            $response = $this->getTrackerResponse($exchangeRate->getTrackerUrl());
            return (float)$this->getValueFromJson($response, $exchangeRate->getValueKey());
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to connect to exchange rate tracker');
        }
    }

    /**
     * @param \stdClass|array $json
     * @param array $key
     * @return mixed
     */
    protected function getValueFromJson($json, array $key)
    {
        foreach ($key as $part) {
            if (preg_match('/\[([0-9]+)\]/', $part, $match)) { //looking for "[n]" syntax as an array
                if (isset($json[$match[1]])) {
                    $json = $json[$match[1]];
                } else {
                    throw new \RuntimeException('Failed to parse exchange rate response from tracker');
                }
            } else {
                if (isset($json->$part)) {
                    $json = $json->$part;
                } else if(isset($json[$part])) {
                    $json = $json[$part];
                } else {
                    throw new \RuntimeException('Failed to parse exchange rate response from tracker');
                }
            }
        }

        return $json;
    }

}
