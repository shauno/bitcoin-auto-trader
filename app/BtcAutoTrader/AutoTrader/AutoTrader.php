<?php

namespace BtcAutoTrader\AutoTrader;

use BtcAutoTrader\Api\BitX;
use BtcAutoTrader\Errors\ErrorMessagesInterface;
use BtcAutoTrader\Errors\ErrorMessageTrait;
use BtcAutoTrader\ExchangeRates\ExchangeRate;
use BtcAutoTrader\ExchangeRates\ExchangeRateRepositoryInterface;
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

    public function trade()
    {
        $xbtUsd = $this->exchangeRateRepository->find('XBT', 'USD');
        $xbtZar = $this->exchangeRateRepository->find('XBT', 'ZAR');
        $usdZar = $this->exchangeRateRepository->find('USD', 'ZAR');

        if (!$xbtUsd->sanityCheck() || !$xbtZar->sanityCheck() || !$usdZar->sanityCheck()) {
            //todo, more granular check and reporting
            $this->addError('exchange_rate', 'One or more exchange rates failed the sanity check');
            return false;
        }

        $percentDifference = $this->calculateDifference($xbtUsd, $xbtZar, $usdZar);

        if ($percentDifference <= 0.03) { //buy buy buy!
            dd('NO, not buying now!');
            //get my zar balance
            if (($zarBalance = $this->bitXApi->getAccountBalance('ZAR')) === false) {
                $this->setErrors($this->bitXApi->getErrors());
                return false;
            }

            //place market order (instantly filled, not ask/bid)
            if (($order = $this->bitXApi->placeBuyMarketOrder('XBT', 'ZAR', 2 /*$zarBalance*/)) === false) {
                $this->setErrors($this->bitXApi->getErrors());
                return false;
            }

            //TODO, clean this shit up yo :smh
            $orderModel = $this->orderRepository->create($order->order_id, 'BUY');
            $orderDetails = $this->bitXApi->getOrderDetails($order->order_id);
            $orderModel = $this->orderRepository->update($order->order_id, $orderDetails);
        } else if ($percentDifference >= 0.06) { //sell sell sell!
            //get my xbt balance
            if (($xbtBalance = $this->bitXApi->getAccountBalance('XBT')) === false) {
                $this->setErrors($this->bitXApi->getErrors());
                return false;
            }

            //place market order (instantly filled, not ask/bid)
            if (($order = $this->bitXApi->placeSellMarketOrder('XBT', 'ZAR', 0.0005 /*$zarBalance*/)) === false) {
                $this->setErrors($this->bitXApi->getErrors());
                return false;
            }

            $orderModel = $this->orderRepository->create($order->order_id, 'SELL');
            $orderDetails = $this->bitXApi->getOrderDetails($order->order_id);
            $orderModel = $this->orderRepository->update($order->order_id, $orderDetails);
        }
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
}
