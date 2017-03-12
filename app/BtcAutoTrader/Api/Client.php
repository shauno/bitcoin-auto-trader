<?php

namespace BtcAutoTrader\Api;

use BtcAutoTrader\Errors\ErrorMessagesInterface;
use BtcAutoTrader\Errors\ErrorMessageTrait;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function get(string $url, array $options = [])
    {
        try {
            $response = $this->client->get($url, $options);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $url
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function post(string $url, array $options = [])
    {
        try {
            $response = $this->client->post($url, $options);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw $e;
        }

    }
}
