<?php

namespace App\Http\Controllers;

use BtcAutoTrader\AutoTrader\AutoTrader;

class AutoTraderController extends Controller
{
    public function trade(AutoTrader $autoTrader)
    {
        try {
            $trade = $autoTrader->trade();

            if ($autoTrader->hasErrors()) {
                return response($autoTrader->getErrors(), 400);
            }

            return response($trade);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}