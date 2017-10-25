<?php

namespace App\Http\Controllers;

use BtcAutoTrader\Orders\OrderRepositoryInterface;

class PageController extends Controller
{
    public function home(OrderRepositoryInterface $orderRepository)
    {
        return view('home')
            ->with('orders', $orderRepository->findAll())
            ->with('lastOrder', $orderRepository->getLastOrder())
            ->with('percentageBuy', getenv('BUY_GAP')*100)
            ->with('percentageSell', getenv('SELL_GAP')*100);
    }
}