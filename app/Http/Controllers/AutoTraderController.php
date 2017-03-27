<?php

namespace App\Http\Controllers;

use BtcAutoTrader\Api\BitX;
use BtcAutoTrader\AutoTrader\AutoTrader;
use BtcAutoTrader\Orders\OrderRepositoryInterface;
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

    public function orderDetails(string $order_id, BitX $bitXApi, OrderRepositoryInterface $orderRepository)
    {
        $orderDetails = $bitXApi->getOrderDetails($order_id);
        return $orderRepository->update($order_id, $orderDetails);
    }
}