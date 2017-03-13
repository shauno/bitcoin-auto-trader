<?php

namespace BtcAutoTrader\Orders;

interface OrderRepositoryInterface
{
    /**
     * @param string $order_id
     * @param string $type
     * @return Order
     */
    public function create(string $order_id, string $type) : Order;

    //TODO I don't like just dumping in the object :/
    /**
     * @param string $order_id
     * @param \stdClass $details
     * @return Order
     */
    public function update(string $order_id, \stdClass $details) : Order;
}