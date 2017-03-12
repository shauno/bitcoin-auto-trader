<?php

namespace BtcAutoTrader\Orders;

interface OrderRepositoryInterface
{
    /**
     * @param string $bitx_order_id
     * @param string $type
     * @return Order
     */
    public function create(string $bitx_order_id, $type = 'BUY') : Order;

    //TODO I don't like just dumping in the object :/
    /**
     * @param string $bitx_order_id
     * @param \stdClass $details
     * @return Order
     */
    public function update(string $bitx_order_id, \stdClass $details) : Order;
}