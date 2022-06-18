<?php

namespace App\Services\EzApi\ParamValidators;

use Contracts\ParamValidator;

class IsNumeric extends ParamValidator
{
    public function validate($value, $settings)
    {
        return is_numeric($value);
    }
}
