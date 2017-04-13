<?php

namespace BtcAutoTrader\Orders;

use Illuminate\Support\Collection;

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

    /**
     * Get the last order placed
     *
     * @param string $type If set only get last order of $type
     * @return Order|null
     */
    public function getLastOrder(string $type = null) : ?Order;

    /**
     * @param string $order
     * @param int|null $limit
     * @return Collection
     */
    public function findAll($order = 'DESC', int $limit = null) : Collection;
}