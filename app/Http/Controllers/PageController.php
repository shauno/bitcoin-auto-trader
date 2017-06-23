<?php

namespace App\Http\Controllers;

use BtcAutoTrader\Orders\OrderRepositoryInterface;

class PageController extends Controller
{
    public function home(OrderRepositoryInterface $orderRepository)
    {
        return view('home')
            ->with('orders', $orderRepository->findAll())
            ->with('lastOrder', $orderRepository->getLastOrder());
    }
}