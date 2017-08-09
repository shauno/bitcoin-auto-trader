<?php

namespace App\Http\Controllers;

use BtcAutoTrader\Api\BitX;
use BtcAutoTrader\AutoTrader\AutoTrader;
use BtcAutoTrader\Orders\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutoTraderController extends Controller
{
    public function trade(AutoTrader $autoTrader)
    {
        try {
            $trade = $autoTrader->trade(
                env('BUY_GAP'),
                env('SELL_GAP'),
                env('BITX_ZAR_ACCOUNT_ID'),
                env('BITX_XBT_ACCOUNT_ID')
            );

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

    public function instantBuyOrder(Request $request, AutoTrader $autoTrader)
    {
        if (password_verify($request->get('password'), env('INSTANT_ORDER_PASSWORD'))) {
            $trade = $autoTrader->buy();

            if($autoTrader->hasErrors()) {
                Log::warning(__METHOD__.'(): Errors found: '.$autoTrader->getErrors());
                return response($autoTrader->getErrors(), 400);
            }

            return response($trade);
        } else {
            return response('No, not for you', 403);
        }
    }

    public function instantSellOrder(Request $request, AutoTrader $autoTrader)
    {
        if (password_verify($request->get('password'), env('INSTANT_ORDER_PASSWORD'))) {
            $trade = $autoTrader->sell();

            if($autoTrader->hasErrors()) {
                Log::warning(__METHOD__.'(): Errors found: '.$autoTrader->getErrors());
                return response($autoTrader->getErrors(), 400);
            }

            return response($trade);
        } else {
            return response('No, not for you', 403);
        }
    }

}