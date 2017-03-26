<?php

namespace App\Http\Controllers;

use BtcAutoTrader\AutoTrader\AutoTrader;
use Illuminate\Support\Facades\Log;

class AutoTraderController extends Controller
{
    public function trade(AutoTrader $autoTrader)
    {
        try {
            $trade = $autoTrader->trade();

            if ($autoTrader->hasErrors()) {
                Log::warning(__METHOD__.'(): Errors found: '.$autoTrader->getErrors());
                return response($autoTrader->getErrors(), 400);
            }

            return response($trade);
        } catch (\Exception $e) {
            Log::warning(__METHOD__.'(): Exception: '.$e->getMessage());
            return response($e->getMessage(), 500);
        }
    }
}