<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeRateLog
 * @property integer $id
 * @property string from_iso
 * @property string to_iso
 * @property float rate
 * @property string created_at
 * @property string updated_at
 */
class ExchangeRateLog extends Model implements HasRateInterface
{
    protected $fillable = [
        'from_iso',
        'to_iso',
        'rate',
    ];

    /**
     * @return float
     */
    public function getRate() : float
    {
        return (float)$this->rate;
    }
}