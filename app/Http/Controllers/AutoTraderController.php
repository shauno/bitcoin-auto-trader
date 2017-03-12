<?php

namespace App\Http\Controllers;

use BtcAutoTrader\AutoTrader\AutoTrader;

class AutoTraderController extends Controller
{
    public function trade(AutoTrader $autoTrader)
    {
        if ($trade = $autoTrader->trade()) {
            dd($trade);
        } else {
            dd($autoTrader->getErrors());
        }
    }
}