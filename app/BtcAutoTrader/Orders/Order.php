<?php

namespace BtcAutoTrader\Orders;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @property string order_id
 * @property float rate
 * @property string $type
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}