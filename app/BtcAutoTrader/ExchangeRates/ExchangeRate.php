<?php

namespace BtcAutoTrader\ExchangeRates;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeRate
 * @property integer $id
 * @property string from_iso
 * @property string to_iso
 * @property float rate
 * @property string tracker_url
 * @property string value_key
 * @property string created_at
 * @property string updated_at
 */
class ExchangeRate extends Model implements HasRateInterface
{
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'tracker_url',
        'value_key',
        'created_at'
    ];

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFromIso()
    {
        return $this->from_iso;
    }

    /**
     * @return string
     */
    public function getToIso()
    {
        return $this->to_iso;
    }

    /**
     * @return float
     */
    public function getRate() : float
    {
        return (float)$this->rate;
    }

    /**
     * @return string
     */
    public function getTrackerUrl()
    {
        return $this->tracker_url;
    }

    /**
     * @return array
     */
    public function getValueKey()
    {
        return explode('.', $this->value_key);
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
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

    /**
     * Checks if the exchange rate is not stale (older than 10min), and within some reasonable bounds (not < 0)
     *
     * @return bool
     */
    public function sanityCheck()
    {
        $cachedTime = time() - strtotime($this->getUpdatedAt());

        return $cachedTime < 600 && is_numeric($this->getRate()) && $this->getRate() > 0;
    }
}
