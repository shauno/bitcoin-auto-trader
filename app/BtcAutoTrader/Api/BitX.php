<?php

namespace BtcAutoTrader\Api;

use BtcAutoTrader\Errors\ErrorMessagesInterface;
use BtcAutoTrader\Errors\ErrorMessageTrait;

class BitX implements ErrorMessagesInterface
{
    use ErrorMessageTrait;

    protected $client;
    private $zarAccountId;
    private $xbtAccountId;
    protected $auth = [];

    /**
     * @param Client $client
     * @param string $zarAccountId
     * @param string $xbtAccountId
     */
    public function __construct(Client $client, string $zarAccountId, string $xbtAccountId)
    {
        $this->client = $client;
        $this->zarAccountId = $zarAccountId;
        $this->xbtAccountId = $xbtAccountId;
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
     * @param string $accountId
     * @return bool|float
     */
    protected function getAccountBalance(string $accountId)
    {
        try {
            $options = ['auth' => $this->auth];
            $result = $this->client->get('https://api.mybitx.com/api/1/balance', $options);
            foreach ($result->balance as $account) {
                if ($account->account_id === $accountId) {
                    return $account->balance;
                }
            }

            $this->addError('api', 'No account with provided accountId found');
            return false;
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }

    public function getZarAccountBalance()
    {
        return $this->getAccountBalance($this->zarAccountId);
    }

    public function getXbtAccountBalance()
    {
        return $this->getAccountBalance($this->xbtAccountId);
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
    public function placeBuyMarketOrder(string $from_iso, string $to_iso, float $amount)
    {
        try {
            $options = [
                'auth' => $this->auth,
                'form_params' => [
                    'pair' => $from_iso.$to_iso,
                    'type' => 'BUY',
                    'counter_volume' => round($amount, 2),
                    'base_account_id' => $this->xbtAccountId,
                    'counter_account_id' => $this->zarAccountId,
                ]
            ];
            $order = $this->client->post('https://api.mybitx.com/api/1/marketorder', $options);

            if (isset($order->error)) {
                $this->addError('api', $order->error);
                return false;
            }

            return $order;
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }

    /**
     * Creates a market order. A market order will immediately sell as much $from_iso as possible with the specified
     * $amount, so you don't need to calculate the ask price you want to specify
     *
     * @param string $from_iso
     * @param string $to_iso
     * @param float $amount
     * @return array|bool
     */
    public function placeSellMarketOrder(string $from_iso, string $to_iso, float $amount)
    {
        try {
            $options = [
                'auth' => $this->auth,
                'form_params' => [
                    'pair' => $from_iso.$to_iso,
                    'type' => 'SELL',
                    'base_volume' => sprintf('%.8F', $amount),
                    'base_account_id' => $this->xbtAccountId,
                    'counter_account_id' => $this->zarAccountId,
                ]
            ];
            $order = $this->client->post('https://api.mybitx.com/api/1/marketorder', $options);

            if (isset($order->error)) {
                $this->addError('api', $order->error);
                return false;
            }

            return $order;
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }

    public function getOrderDetails($order_id)
    {
        try {
            $options = ['auth' => $this->auth];
            return $this->client->get('https://api.mybitx.com/api/1/orders/'.$order_id, $options);
        } catch (\Exception $e) {
            $this->addError('api', $e->getMessage());
            return false;
        }
    }
}
