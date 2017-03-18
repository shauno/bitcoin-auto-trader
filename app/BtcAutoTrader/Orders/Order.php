<?php

namespace BtcAutoTrader\Orders;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @property string order_id
 * @property float rate
 */
class Order extends Model
{
    protected $fillable = [
        'order_id',
        'type',
    ];

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }
}