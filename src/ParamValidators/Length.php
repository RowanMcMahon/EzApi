<?php

namespace EzApi\ParamValidators;

use EzApi\Contracts\ParamValidator;

class Length extends ParamValidator
{
    public function validate($value, $settings)
    {
        if (strlen($value) > $settings['max'])
        {
            $this->setMessage('The value must be less than ' . $settings['max'] . ' characters.');
            return false;
        }

        if (strlen($value) < $settings['min'])
        {
            $this->setMessage('The value must be greater than ' . $settings['min'] . ' characters.');
            return false;
        }
    }
}
