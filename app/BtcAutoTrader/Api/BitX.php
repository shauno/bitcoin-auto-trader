<?php

namespace BtcAutoTrader\Api;

use BtcAutoTrader\Errors\ErrorMessagesInterface;
use BtcAutoTrader\Errors\ErrorMessageTrait;

class BitX implements ErrorMessagesInterface
{
    use ErrorMessageTrait;

    protected $client;
    protected $auth = [];

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $user
     * @param $pass
     * @return $this
     */
    public function setAuth($user, $pass)
    {
        $this->auth = [$user, $pass];
        return $this;
    }

    /**
     * Get a bitx account balance
     *
     * @param string $asset
     * @return bool|float
     */
    public function getAccountBalance(string $asset)
    {
        try {
            $options = ['auth' => $this->auth];
            $result = $this->client->get('https://api.mybitx.com/api/1/balance', $options);
            $balance = 0;
            foreach ($result->balance as $account) {
                if ($account->asset === $asset) {
                    $balance += $account->balance;
                }
            }

            return $balance;
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $from_iso
     * @param string $to_iso
     * @return bool|array
     */
    public function getOrderBook(string $from_iso, string $to_iso)
    {
        try {
            $options = ['auth' => $this->auth];
            return $this->client->get('https://api.mybitx.com/api/1/orderbook?pair='.$from_iso.$to_iso, $options);
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }

    /**
     * Creates a market order. A market order will immediately purchase as much $from_iso as possible with the specified
     * $amount, so you don't need to calculate the bid you want to place
     *
     * @param string $from_iso
     * @param string $to_iso
     * @param float $amount
     * @return array|bool
     */
    public function placeMarketOrder(string $from_iso, string $to_iso, float $amount)
    {
        try {
            $options = [
                'auth' => $this->auth,
                'form_params' => [
                    'pair' => $from_iso.$to_iso,
                    'type' => 'BUY',
                    'counter_volume' => round($amount, 2),
                ]
            ];
            return $this->client->post('https://api.mybitx.com/api/1/marketorder', $options);
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }
}
