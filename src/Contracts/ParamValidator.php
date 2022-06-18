<?php

namespace EzApi\Contracts;

abstract class ParamValidator
{
    protected $message;

    abstract public function validate($value, $settings);

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
