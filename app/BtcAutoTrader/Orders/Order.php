<?php

namespace BtcAutoTrader\Orders;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'type',
    ];
}