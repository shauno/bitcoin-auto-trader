<?php

namespace BtcAutoTrader\AutoTrader;

use BtcAutoTrader\Api\BitX;
use BtcAutoTrader\Errors\ErrorMessagesInterface;
use BtcAutoTrader\Errors\ErrorMessageTrait;
use BtcAutoTrader\ExchangeRates\ExchangeRate;
use BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryInterface;
use BtcAutoTrader\Orders\Order;
use BtcAutoTrader\Orders\OrderRepositoryInterface;

class AutoTrader implements ErrorMessagesInterface
{
    use ErrorMessageTrait;

    protected $exchangeRateRepository;
    protected $bitXApi;
    protected $orderRepository;

    public function __construct(
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        BitX $bitXApi,
        OrderRepositoryInterface $orderRepository

    ) {
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->bitXApi = $bitXApi;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param float $buyGap Percent / 100 between exchanges to signal a buy order (eg 0.04 = 4%)
     * @param float $sellGap Percent / 100 between exchanges to signal a sell order (eg 0.08 = 8%)
     * @param $zarAccountId Luno ZAR account to use for the trade
     * @param $xbtAccountId Luno XBT account to use for the trade
     * @return Order|null
     */
    public function trade(float $buyGap = 0.02, float $sellGap = 0.06, $zarAccountId, $xbtAccountId) : ?Order
    {
        $xbtUsd = $this->exchangeRateRepository->find('XBT', 'USD');
        $xbtZar = $this->exchangeRateRepository->find('XBT', 'ZAR');
        $usdZar = $this->exchangeRateRepository->find('USD', 'ZAR');

        if (!$xbtUsd->sanityCheck() || !$xbtZar->sanityCheck() || !$usdZar->sanityCheck()) {
            //todo, more granular check and reporting
            $this->addError('exchange_rate', 'One or more exchange rates failed the sanity check');
            return null;
        }

        $percentDifference = $this->calculateDifference($xbtUsd, $xbtZar, $usdZar);
        $lastOrder = $this->orderRepository->getLastOrder();

        //TODO, consider only buying if price in USD is trending up. ZAR might just be selling off more aggressively and then we're buying in on the way down :(
        if ($percentDifference <= $buyGap && (is_null($lastOrder) || $lastOrder->getType() != 'BUY')) { //buy buy buy!
            return $this->buy();
        } else if ($percentDifference >= $sellGap && (is_null($lastOrder) || $lastOrder->getType() != 'SELL')) { //sell sell sell!
            //make sure the rate is not actually worse than when we bought
            if ($lastOrder && $xbtZar->getRate() <= $lastOrder->getRate()) {
                $this->addError('rate', 'The current buy rate is worse than what was paid');
                return null;
            }

            return $this->sell();
        }

        return null;
    }

    /**
     * @param ExchangeRate $cheap The offshore rate which is cheaper than local. Needs to be multiplied by the
     *                            $currencyRate to be compared to $expensive
     * @param ExchangeRate $expensive The local rate which is more expensive than $cheap
     * @param ExchangeRate $currencyRate The currency rate between purchase currencies of $cheap and $expensive
     * @return float
     */
    protected function calculateDifference(ExchangeRate $cheap, ExchangeRate $expensive, ExchangeRate $currencyRate)
    {
        $offshoreRate = $cheap->getRate() * $currencyRate->getRate();

        //calculate the % difference between cheap (offshore) and expensive (local)
        $diff = $expensive->getRate() - $offshoreRate;

        //return the % difference between the rates
        return $diff / $expensive->getRate();
    }

    public function buy()
    {
        //get my zar balance
        if (($zarBalance = $this->bitXApi->getZarAccountBalance()) === false) {
            $this->setErrors($this->bitXApi->getErrors());
            return null;
        }

        //todo, figure our why you can't buy with your entire balance
        //place market order (instantly filled, not ask/bid)
        if (($order = $this->bitXApi->placeBuyMarketOrder('XBT', 'ZAR', floor($zarBalance))) === false) {
            $this->setErrors($this->bitXApi->getErrors());
            return null;
        }

        $this->orderRepository->create($order->order_id, 'BUY');

        sleep(5); //not documented in the API, but it seems the purchase details are not retrievable immediately. So hax :)

        if (!$orderDetails = $this->bitXApi->getOrderDetails($order->order_id)) {
            $this->setErrors($this->bitXApi->getErrors());
            return null;
        }

        return $this->orderRepository->update($order->order_id, $orderDetails);
    }

    public function sell()
    {
        //get my xbt balance
        if (($xbtBalance = $this->bitXApi->getXbtAccountBalance()) === false) {
            $this->setErrors($this->bitXApi->getErrors());
            return null;
        }

        //TODO, figure out why you can't sell your entire balance
        //place market order (instantly filled, not ask/bid)
        if (($order = $this->bitXApi->placeSellMarketOrder('XBT', 'ZAR', $xbtBalance * 0.98)) === false) {
            $this->setErrors($this->bitXApi->getErrors());
            return null;
        }

        sleep(5);

        $this->orderRepository->create($order->order_id, 'SELL');

        if (!$orderDetails = $this->bitXApi->getOrderDetails($order->order_id)) {
            $this->setErrors($this->bitXApi->getErrors());
            return null;
        }

        return $this->orderRepository->update($order->order_id, $orderDetails);
    }
}
