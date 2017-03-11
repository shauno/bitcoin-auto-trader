<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeRate
 * @property integer $id
 * @property string from_iso
 * @property string to_iso
 * @property float rate
 * @property string created_at
 * @property string updated_at
 */
class ExchangeRate extends Model
{
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param float $rate
     * @return $this
     */
    public function setRate(float $rate)
    {
        $this->rate = $rate;
        return $this;
    }
}
