<?php

namespace BtcAutoTrader\Errors;

use Illuminate\Support\MessageBag;

trait ErrorMessageTrait
{
    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * @param MessageBag $errors
     */
    protected function setErrors(MessageBag $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param string $key
     * @param string $message
     */
    protected function addError(string $key, string $message)
    {
        $this->errors = $this->errors ?? new MessageBag();
        $this->errors->add($key, $message);
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
