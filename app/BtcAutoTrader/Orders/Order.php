<?php

namespace BtcAutoTrader\Orders;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @property string order_id
 */
class Order extends Model
{
    protected $fillable = [
        'order_id',
        'type',
    ];
}