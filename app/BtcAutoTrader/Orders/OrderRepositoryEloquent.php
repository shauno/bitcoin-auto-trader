<?php

namespace BtcAutoTrader\Orders;

class OrderRepositoryEloquent implements OrderRepositoryInterface
{
    /**
     * @param string $order_id
     * @param string $type
     * @return Order
     */
    public function create(string $order_id, string $type): Order
    {
        $order = new Order();
        $order->fill([
            'order_id' => $order_id,
            'type' => $type,
        ])->save();

        return $order;
    }

    /**
     * @param string $order_id
     * @param \stdClass $details
     * @return Order
     */
    public function update(string $order_id, \stdClass $details): Order
    {
        $order = (new Order())->where('order_id', $order_id)->first();

        $order->creation_timestamp = $details->creation_timestamp;
        $order->expiration_timestamp = $details->expiration_timestamp;
        $order->completed_timestamp = $details->completed_timestamp;
        $order->type = $details->type;
        $order->state = $details->state;
        $order->limit_price = $details->limit_price;
        $order->limit_volume = $details->limit_volume;
        $order->base = $details->base;
        $order->counter = $details->counter;
        $order->fee_base = $details->fee_base;
        $order->fee_counter = $details->fee_counter;
        $order->pair = $details->pair;
        $order->trades = json_encode($details->trades);
        $order->btc = $details->btc;
        $order->zar = $details->zar;
        $order->fee_btc = $details->fee_btc;
        $order->fee_zar = $details->fee_zar;

        $order->save();
        return $order;
    }
}