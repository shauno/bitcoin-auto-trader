<?php

namespace BtcAutoTrader\ExchangeRates;

interface HasRateInterface
{
    /**
     * @return float
     */
    public function getRate() : float;
}