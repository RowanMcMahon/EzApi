<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class Accepts extends ParamValidator
{
    public function validate($value, $settings)
    {
        if (!in_array($value, $settings))
        {
            $this->setMessage('The value must be one of the following: ' . implode(', ', $settings));
            return false;
        }
    }
}
